<template>
    <div class="p-6 pl-0 text-gray-900" style="position: sticky; top: 0; background-color: white; z-index: 1;">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <Link :href="route('chat.index')" class="nav-link" :class="{ active: isChatRoom }">
                    Chats
                </Link>
            </li>
            <li class="nav-item">
                <Link :href="route('contact.index')" class="nav-link" :class="{ active: isContacts }">
                    Contacts
                </Link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

// Get the current Inertia page
const page = usePage();

// Compute active states
const isChatRoom = computed(() => page.url.startsWith("/chat"));
const isContacts = computed(() => page.url.startsWith("/contact"));
</script>
