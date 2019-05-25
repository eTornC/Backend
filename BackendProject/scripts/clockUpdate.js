"use strict";

const axios = require('axios');

setInterval(() => {

    axios.post('http://localhost/clockUpdate')
        .then(res => {

            const d = new Date();

            const hour = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();

            if (res.data.done) {
                console.log('Clock Update Success ' + hour);
            } else {
                console.log('Clock Update FAIL ' + hour);
            }
        });

}, 59000);
