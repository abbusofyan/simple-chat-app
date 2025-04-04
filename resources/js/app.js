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
            .use(ZiggyVue);

            app.config.globalProperties.$filters = {
                formatDate(dateString) {
                    const date = dayjs(dateString);
                    const formattedTime = date.format('h:mm A');

                    const isToday = dayjs().isSame(date, 'day');

                    return `${formattedTime}, ${isToday ? 'Today' : ''}`;
                }
            }

            const userId = props.initialPage.props.auth?.user?.id;

            app.config.globalProperties.$echo = createEcho(userId);

            app.mount(el);

            // window.Echo.channel(`notification.${props.initialPage.props.auth.user.id}`).listen(".MessageSent", (data) => {
            //     props.conversation.messages.push(data.message);
            // });
    },
    progress: {
        color: '#4B5563',
    },
});
