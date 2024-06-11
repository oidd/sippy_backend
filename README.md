# Sippy (Laravel Backend)

## About this project
This is a training project, a huge part of it I wrote during hackathon that lasted two days.

I believe that the idea of project itself is not essential at this point, but we were building something like a meeting site, but for the large group of people. 

The main window of application (would that be a web app or a mobile app) is a map, 
where anyone could put a point with a description and desirable gender or age boundaries of who can see this point. 
Anyone who is within these boundaries can see this point and make a 'Call' to it (firstly this was called a 'request', 
but in backend it would be a pain dealing with this naming). 
The user that made a point could see those 'Calls' and approve or decline them.

And this is a backend for this project.

## Features
At this point I implemented:
- Login and registration
- Creating point and its description
- Decision if a user can see a certain point
- Making a Call, approving or declining it
- Getting nearest points around user (we are receiving their geolocation)

(Honestly you should just look through a code, since it would be more meaningfully than reading my writings)

Will implement later:
- Websockets integration:
  - Getting online users
  - Broadcasting an event of creation a point (only to users that should see this point)
  - Same thing with updating or deletion

## Auth
For authentication purposes i'm using a little package that adds new guard that resolves user based on provided JWT token: https://github.com/oidd/laravel-jwt-auth/

## Storing geodata
For storing geo data we're using a postgres addon PostGis, which might be a bit of overkill for this little project. You might see there are raw DB queries that uses PostGis functions, witch is bothers me but i suppose i better leave these queries as they are in order to maintain readability.

## If you happend to want to try this code yourself
There are seeders to create a few Users and Points along with their descriptions.
Make sure to properly install my package for JWT auth (as it described in related repository) and fill the config files.


This project is - obviously - still under development, but thank you for showing interest in my work.
