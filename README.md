# Transition Network Content Delivery System

**CDS** is a web site and service which allows Transition Hubs, Initiatives, and the public to get information about the global Transition movement.

## On site ##
Users are able to login and view / add initiative data

## On site user permissions overview ##

Users can engage with the CDS site as one of the following users:

### Not Logged In ###
- `Initiatives` - View All
- `Healthchecks & Private Data` - No Permissions

### Initiative User ###
- `Initiatives` - View All. Create (un-approved)
- `Healthchecks & Private Data` - View, Create, Edit data associated with own initiatives

### Hub User ###
- `Initiatives` - View All. Create. Approve, Edit, Delete initiatives in hub
- `Healthchecks & Private Data` - View, Create, Edit data associated with hub initiatives

### Super Hub User & Administrator ###
- `Initiatives` - View All. Create. Approve, Edit, Delete All.
- `Healthchecks & Private Data` - View, Create, Edit All.

## Healthcheck ##
Hubs are able to healthcheck all initiatives within their area. Superhubs can healthcheck all initiatives.

Reporting is provided on healthcheck dates for initiatives.

## Off Site ##

Third parties are able to connect to the CDS site from their own websites using the CDS (Wordpress) API to obtain initiative data for a hub.

They are also able to display a map showing hub initiatives using an iframe.
