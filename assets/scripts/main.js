// Import router
import Router from './router';

// Import local deps
import common from './routes/common';

import account from './routes/account';

//iframe map page
import map from './routes/map_route';

//homepage
import home from './routes/home';

//hub_list
import hub_list from './routes/hub_list'

//search results
import search_initiatives from './routes/map_route';


import single_healthchecks from './routes/single_healthcheck'

import single_initiatives from './routes/single';
import tax_hub from './routes/single';

// Import ajaxForm
import './ajax-form';

// Boostrap
import 'bootstrap/dist/js/bootstrap.js'; // All of Bootstrap JS

// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
// You add additional pages to this array by referencing the the body class
// and creating the js file in the routes directory. Remember to import the
// file as per the common example near the top of this file.
const routes = {
  common,
  account,
  map,
  home,
  hub_list,
  search_initiatives,
  single_initiatives,
  single_healthchecks,
  tax_hub
};

// Load Events
document.addEventListener('DOMContentLoaded', () => new Router(routes).loadEvents());

// Window Loaded
window.onload = () => new Router(routes).loadEvents('loaded');
