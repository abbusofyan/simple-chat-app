import '../css/app.css';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import dayjs from 'dayjs';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createEcho } from './plugins/echo';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast);

            app.config.globalProperties.$filters = {
                formatDate(dateString) {
                    const date = dayjs(dateString);
                    const formattedTime = date.format('h:mm A');

                    const isToday = dayjs().isSame(date, 'day');

                    return `${formattedTime}, ${isToday ? 'Today' : ''}`;
                }
            }

            const userId = props.initialPage.props.auth?.user?.id;

            const echo = createEcho(userId);

            app.config.globalProperties.$echo = echo;

            app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
