// Import router
import Router from './router';

// Import local deps
import common from './routes/common';

import account from './routes/account';

//iframe map page
import map from './routes/map_url_params';

//homepage
import home from './routes/home';

//hub_list
import hub_list from './routes/zz_hub_list'

//trainer_list
import post_type_archive_trainers from './routes/trainer_list'

//search results
import search_groups from './routes/map_route';

import single_initiatives from './routes/single';
import single_healthchecks from './routes/single_healthcheck'
import single_trainers from './routes/single_trainer';
import tax_hub from './routes/single';

import edit_group from './routes/edit_page';

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
  search_groups,
  single_initiatives,
  single_healthchecks,
  single_trainers,
  tax_hub,
  post_type_archive_trainers,
  edit_group
};

// Load Events
document.addEventListener('DOMContentLoaded', () => new Router(routes).loadEvents());

// Window Loaded
window.onload = () => new Router(routes).loadEvents('loaded');
