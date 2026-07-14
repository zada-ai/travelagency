import './bootstrap';

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, Thumbs, Keyboard } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/thumbs';

window.Swiper = Swiper;
window.SwiperModules = {
    Navigation,
    Pagination,
    Autoplay,
    Thumbs,
    Keyboard
};