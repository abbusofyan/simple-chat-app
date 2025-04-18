<template>
    <div class="chat">
        <div v-if="conversation" class="chat-header clearfix" style="position: sticky; top: 0; background-color: white; z-index: 1;">
            <div class="row">
                <div class="col-lg-6">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                    </a>
                    <div class="chat-about">
                        <h6 class="m-b-0">{{conversation ? conversation.talking_to.name : ''}}</h6>
                        <small>{{conversation ? 'Last seen : 2 minutes ago' : ''}}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-history" @scroll="handleScroll" style="height: 340px; overflow-y: auto;">
            <ul class="m-b-0">
                <template v-if="conversation" v-for="message in conversation.messages">
                    <li class="clearfix">
                        <div class="message-data" :class="usePage().props.auth.user.id == message.sender_id ? 'text-right' : ''">
                            <span class="message-data-time">{{$filters.formatDate(message.created_at)}}</span>
                        </div>
                        <div class="message" :class="usePage().props.auth.user.id == message.sender_id ? 'float-right my-message' : 'other-message'">{{message.text}}</div>
                    </li>
                </template>
                <div ref="bottomOfMessages"></div>
            </ul>
        </div>
        <div v-if="conversation" class="chat-message clearfix" style="position: sticky; bottom: 0; background-color: white; z-index: 1;">
            <div class="input-group mb-0">
                <input type="text" v-model="form.text" class="form-control" placeholder="Enter text here..." @keydown.enter="send()">
                <button type="button" @click="send()" class="btn btn-primary" name="button">Send</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import { defineProps, ref, nextTick, watch, computed, onMounted, onUnmounted, getCurrentInstance } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';

const { proxy } = getCurrentInstance();

const props = defineProps({
    conversation: Object
})

proxy.$echo.channel(`chat.${props.conversation?.id}`).listen(".MessageSent", (data) => {
    props.conversation.messages.push(data.message);
    scrollToBottom();
});

const bottomOfMessages = ref(null);

const scrollToBottom = () => {
    nextTick(() => {
        bottomOfMessages.value?.scrollIntoView({ behavior: 'smooth' });
    });
};

onMounted(() => {
    loadOlderMessages();
    scrollToBottom();
});

const form = useForm({
    text: null,
    conversation_id: props.conversation?.id,
    sender_id: usePage().props.auth.user.id,
    receiver_id: props.conversation?.talking_to.id
})

const send = async () => {
    try {
        if (form.text) {
            await axios.post('/chat/send', form);
            form.text = null
            scrollToBottom();
        }
    } catch (error) {
        console.error("Failed to fetch conversation:", error);
    }
};

const isLoadingOlder = ref(null);
const hasMoreMessages = ref(null);

const handleScroll = () => {
    const scrollTop = event.target.scrollTop;
    if (scrollTop === 0) {
        loadOlderMessages();
    }
}

const loadOlderMessages = async() => {
    isLoadingOlder.value = true;
    const firstMessageId = props.conversation.messages ? props.conversation.messages[0]?.id : null
    try {
        const response = await axios.get(`/chat/${props.conversation.id}/messages`, {
            params: {before: firstMessageId}
        })

        const oldMessages = response.data.messages
        if (oldMessages.length === 0) {
            hasMoreMessages.value = false
        } else {
            props.conversation.messages.unshift(...oldMessages)
        }
    } catch (e) {

    }
}

</script>

<style lang="css" scoped>
.card {
    background: #fff;
    transition: .5s;
    border: 0;
    margin-bottom: 30px;
    border-radius: .55rem;
    position: relative;
    width: 100%;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
}
.chat-app .people-list {
    width: 280px;
    position: absolute;
    left: 0;
    top: 0;
    padding: 20px;
    z-index: 7
}

.chat-app .chat {
    margin-left: 280px;
    border-left: 1px solid #eaeaea
}

.people-list {
    -moz-transition: .5s;
    -o-transition: .5s;
    -webkit-transition: .5s;
    transition: .5s
}

.people-list .chat-list li {
    padding: 10px 15px;
    list-style: none;
    border-radius: 3px
}

.people-list .chat-list li:hover {
    background: #efefef;
    cursor: pointer
}

.people-list .chat-list li.active {
    background: #efefef
}

.people-list .chat-list li .name {
    font-size: 15px
}

.people-list .chat-list img {
    width: 45px;
    border-radius: 50%
}

.people-list img {
    float: left;
    border-radius: 50%
}

.people-list .about {
    float: left;
    padding-left: 8px
}

.people-list .status {
    color: #999;
    font-size: 13px
}

.chat .chat-header {
    padding: 15px 20px;
    border-bottom: 2px solid #f4f7f6
}

.chat .chat-header img {
    float: left;
    border-radius: 40px;
    width: 40px
}

.chat .chat-header .chat-about {
    float: left;
    padding-left: 10px
}

.chat .chat-history {
    padding: 20px;
    border-bottom: 2px solid #fff
}

.chat .chat-history ul {
    padding: 0
}

.chat .chat-history ul li {
    list-style: none;
    margin-bottom: 30px
}

.chat .chat-history ul li:last-child {
    margin-bottom: 0px
}

.chat .chat-history .message-data {
    margin-bottom: 15px
}

.chat .chat-history .message-data img {
    border-radius: 40px;
    width: 40px
}

.chat .chat-history .message-data-time {
    color: #434651;
    padding-left: 6px
}

.chat .chat-history .message {
    color: #444;
    padding: 18px 20px;
    line-height: 26px;
    font-size: 16px;
    border-radius: 7px;
    display: inline-block;
    position: relative
}

.chat .chat-history .message:after {
    bottom: 100%;
    left: 7%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-bottom-color: #fff;
    border-width: 10px;
    margin-left: -10px
}

.chat .chat-history .my-message {
    background: #96e0d1
}

.chat .chat-history .my-message:after {
    bottom: 100%;
    left: 30px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-bottom-color: #efefef;
    border-width: 10px;
    margin-left: -10px
}

.chat .chat-history .other-message {
    background: #efefef;
    text-align: right
}

.chat .chat-history .other-message:after {
    border-bottom-color: #efefef;
    left: 93%
}

.chat .chat-message {
    padding: 20px
}

.online,
.offline,
.me {
    margin-right: 2px;
    font-size: 8px;
    vertical-align: middle
}

.online {
    color: #86c541
}

.offline {
    color: #e47297
}

.me {
    color: #1d8ecd
}

.float-right {
    float: right
}

.clearfix:after {
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0
}

@media only screen and (max-width: 767px) {
    .chat-app .people-list {
        height: 465px;
        width: 100%;
        overflow-x: auto;
        background: #fff;
        left: -400px;
        display: none
    }
    .chat-app .people-list.open {
        left: 0
    }
    .chat-app .chat {
        margin: 0
    }
    .chat-app .chat .chat-header {
        border-radius: 0.55rem 0.55rem 0 0
    }
    .chat-app .chat-history {
        height: 300px;
        overflow-x: auto
    }
}

@media only screen and (min-width: 768px) and (max-width: 992px) {
    .chat-app .chat-list {
        height: 650px;
        overflow-x: auto
    }
    .chat-app .chat-history {
        height: 600px;
        overflow-x: auto
    }
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
    .chat-app .chat-list {
        height: 480px;
        overflow-x: auto
    }
    .chat-app .chat-history {
        height: calc(100vh - 350px);
        overflow-x: auto
    }
}
</style>
