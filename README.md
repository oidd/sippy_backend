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

Honestly you should just look through a code, since it would be more meaningfully than reading my writings.

Will implement later:
- Websockets integration:
  - Getting online users
  - Broadcasting an event of creation a point (only to users that should see this point)
  - Same thing with updating or deletion

For storing geo data we're using a postgresql addon PostGis, which might be a bit of overkill for this little project.
