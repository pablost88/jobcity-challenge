# Jobsity Challenge

The application was built using Wordpress 6.0.2 and a LEMP (nginx, php 8, mysql).
The database file is located in the root directory of the application.
The URL of the git repository is https://github.com/pablost88/jobcity-challenge
Here you'll find the folder with the application code.

To access the WordPress Dashboard use these credentials:
Username: pablo
Password: pablo

I finished all the mandatory features. Also, I completed the optional feature 'Search'.

## Mandatory Features
I added movies and actors to the website using the information provided by the API from 'themoviedb'.
These are the endpoint I used:
- https://api.themoviedb.org/3/movie/{movie_id}?api_key=
- https://api.themoviedb.org/3/discover/movie?api_key=
- https://api.themoviedb.org/3/movie/upcoming?api_key=
- https://api.themoviedb.org/3/person/popular?api_key=

I created a plugin called 'Jobsity Challenge'. Inside the plugin, you can find the necessary functionality to implement the application logic.
The application won't work y if this plugin is deactivated.

#### About the requirements:
- Movies should be filterable by name, year, genre and title.
- Actors should be filterable by name and movie.

It wasn't clear to me if the filter should be implemented in the front-end page or the Wordpress admin section related to these post types.
I ended up creating new columns in the admin section of these post types to sort them.

#### Movie Detail Page and Actor Detail Page
Some of the fields are pulled from the API. The pulled fields are the ones that aren't important for the application logic.
My idea was to implement this functionality using WordPress transient, to prevent calling the API every time an actor or movie is shown, but I run out of time.

#### Movie and Actor relationship
The user can add a Movie related to an actor and vice-versa. This can be done from the admin panel of the single actor / single movie.
I created the relationship using the plugin 'Advanced Custom Fields'. The free version of the plugin ACF doesn't allow to add a 'many-to-many' relationship, so I resolve this by adding the relationship manually (function bidirectional_acf_update_value())


#### Search functionality
I added two meta fields to the actors and movies to implement the search formula.  These fields are necessary to save the post count visits and to save the value of the formula for each movie or actor post.
Every time an actor or movie is accessed, these meta fields are updated.
The logic is implemented inside the functions.php file.
Then, these fields are used in the function
