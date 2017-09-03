# Url-Shortener
Xsolla Summer School final application.\
Silex for MVC stuff and routing.\
MySQL and PDO as default Database and db provider.

RESTful API with HTTP basic authentication and a couple of bicycles.

All POST queries must contain JSON data.


POST /api/v1/users/ - register new user {"email":"", "name":"", "password":""}

GET /api/v1/users/me - current user info


GET /api/v1/users/me/shorten_urls - list of user's shorten URLs

GET /api/v1/users/me/shorten_urls/{id} - specific URL info

POST /api/v1/users/me/shorten_urls - add new short URL ("full_link":"")

DELETE /api/v1/users/me/shorten_urls/{id} - delete chosen URL

GET /api/v1/users/me/shorten_urls/{id}/referers - get top 20 referers of a URL

GET /api/v1/users/me/shorten_urls/{id}/[days,hours,min]?from_date=YYYY-MM-DD&to_date=YYYY-MM-DD - get click count statistics for chosen period. If the period is not specified, then statistics for last week, day, hour will be given.
*more specific from_date&to_date coming soon™

GET /api/v1/shorten_urls/{hash} - redirect to a full_link bound to {hash}, where {hash} is "short_link"