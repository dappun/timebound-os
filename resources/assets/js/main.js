import '../../../node_modules/smartmenus-bootstrap/jquery.smartmenus.bootstrap.css';
import '../../../node_modules/select2/dist/css/select2.min.css';

import 'babel-polyfill';
import $ from 'jquery';
import moment from 'moment';
import 'moment-timezone';
import Vue from 'vue';

window.moment = moment;
window.Vue = Vue;

var path_resources = __dirname + "resources/assets";
window.path_resources = path_resources;

require('select2');
require('smartmenus');
require('smartmenus-bootstrap');