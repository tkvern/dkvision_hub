
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: 'body'
// });

$(document).ready(function () {
    // body...
    $('#autotime').on('click', function(e) {
        if($('#configtime').val() == "") {
          return;
        }
        var time = $('#configtime').val().split('_');
        var alignment = $("input[name='payload[time_alignment][]']");
        for(var i=0; i < time.length; i ++ ) {
          alignment[i].value = time[i];
        }
    })
});
