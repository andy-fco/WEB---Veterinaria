import "./bootstrap";

import * as Popper from "@popperjs/core";
window.Popper = Popper;

import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

import Chart from 'chart.js/auto'; // para importar lo de los graficos. 
window.Chart = Chart; 

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    var dropdownElementList = [].slice.call(
        document.querySelectorAll(".dropdown-toggle")
    );

    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
