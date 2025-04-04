import Echo from "laravel-echo";
import Pusher from "pusher-js";

let echoInstance = null;

export function createEcho(userId) {
    if (!userId) {
        console.warn("Echo not initialized: no user ID provided.");
        return null;
    }

    echoInstance = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
    });

    return echoInstance;
}

export function useEcho() {
    if (!echoInstance) {
        console.warn("Echo has not been initialized yet.");
    }
    return echoInstance;
}
