import "../css/main.css";

import naja from 'naja';
window.naja = naja;

document.addEventListener('DOMContentLoaded', function () {
    naja.initialize({ history: false, historyUICache: false});
});