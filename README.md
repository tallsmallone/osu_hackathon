# EDUdine

[OHI/O](http://hack.osu.edu/) 2015 Hackathon Project - comprehensive OSU dining database website

# Team
* Nick Flint - [@tallsmallone](http://github.com/tallsmallone/) - Electrical Engineering Major
* Cailin Pitt - [@CailinPitt](http://github.com/CailinPitt/) - Computer Science Engineering Major
* Stephen Wu - [@wustep](http://github.com/wustep/) - Computer Science Engineering Major

# Description
EDUdine is a OHI/O '15 project creating a prototype for a comprehensive dining database, particularly for Ohio State University BuckID merchants and dining services. 
* Places are scrapped by Ruby and recorded in a SQL database, storing:
  * Address, hours, menu and website links, geographic location (longitude, latitude) 
  * Type(s) of dining service (Swipes, Meal Exchange, Dining Dollars, and BuckID) 
  * Tag(s) (American, Ethnic, Dessert, Pizza/Pasta, Gluten-Free, etc.). 
* Users can click on dining tags or types to sort by them, as well as search by name and tag.

---

# Notes
* The current maps implementation is not recommended for long-term use. Google Maps isn't meant to load multiple locations from addresses! 
  * Longitude and latitude should be stored in the database (under "geo") and then used to place markers on the map.
* Hours for most BuckID vendors was not completed in time. Menu links were also not scraped. 
* Dining service types were aggregated for BuckID and Dining Dollars, but not Meal Exchange and Swipes. 

# Expansion
Posible expansion plans could include:
* Google Places integration
* User system: reviews, ratings, tags, suggestions, etc. 
* List or map to show nearby open places (possibly based on user preferences)
* Function to see if a place is already open, and don't display those locations by default
