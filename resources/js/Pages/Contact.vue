<template>

    <Head title="Contact Page" />

    <AuthenticatedLayout>

        <div class="container">
            <div class="mx-auto py-12 row">
                <div class="col-sm-12 col-md-12 col-lg-6 bg-white d-flex flex-column" style="height: 500px; overflow-y: auto;">
                    <NavPills/>
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            <template v-for="contact in contacts">
                                <li class="clearfix d-flex align-items-center" @click="navigateTo(contact.id)" style="cursor: pointer;">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                                    <div class="about ps-2">
                                        <div class="name">{{ contact.name }}</div>
                                        <div class="status">
                                            <i class="fa fa-circle offline"></i> left 7 mins ago
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavPills from '@/Components/NavPills.vue';
import { router, Head } from '@inertiajs/vue3';

const props = defineProps({
    contacts: Object
})

const navigateTo = (contactId) => {
    router.visit(`/contact/start-chat/${contactId}`);
};

</script>

<style lang="css" scoped>

.people-list {
    transition: .5s;
}

.people-list .chat-list li {
    padding: 10px 15px;
    list-style: none;
    border-radius: 3px;
    display: flex;
    align-items: center;
}

.people-list .chat-list li:hover {
    background: #efefef;
    cursor: pointer;
}

.people-list .chat-list img {
    width: 45px;
    border-radius: 50%;
}

.people-list .about {
    padding-left: 10px;
}

.people-list .status {
    color: #999;
    font-size: 13px;
}

.online,
.offline {
    margin-right: 2px;
    font-size: 8px;
    vertical-align: middle;
}

.online {
    color: #86c541;
}

.offline {
    color: #e47297;
}

@media only screen and (max-width: 767px) {
    .chat-app .people-list {
        width: 100%;
        left: -400px;
        display: none;
    }
    .chat-app .people-list.open {
        left: 0;
    }
}
</style>
