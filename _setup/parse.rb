# Mechanize gets the website and transforms into HTML file.
require 'mechanize'
# Nokogiri gets the website data that could be read later on.
require 'nokogiri'

agent = Mechanize.new
agent.open_timeout = 60
agent.read_timeout = 60
agent.user_agent_alias = "Mac Safari" 
# Initialize new Mechanize agent

diningLocations = agent.get "https://buckid.osu.edu/merchants/list?LocationType=Campus%20Dining"
campusPage = Nokogiri::HTML(diningLocations.body)
# Get HTML page containing dining locations

oddItems = campusPage.css("div[class='item-odd']")
evenItems = campusPage.css("div[class='item-even']")
totalLocations = oddItems.size + evenItems.size

target = open('locations.txt', 'w')
for i in 1..totalLocations / 2
	
		infoTD = oddItems[i - 1]
		target.write ("Name: #{infoTD.css("h4").text.squeeze(" ")}\n")
		target.write ("--#{infoTD.css("p")[0].text.squeeze(" ")}--\n")
		target.write ("--#{infoTD.css("p")[1].text.squeeze(" ")}--\n")
		target.write ("Address: --#{infoTD.css("address").text.squeeze(" ")}--\n")
		target.write ("\n")
		
		infoTD = evenItems[i - 1]
		target.write ("Name: #{infoTD.css("h4").text.squeeze(" ")}\n")
		target.write ("#{infoTD.css("p")[0].text.squeeze(" ")}\n")
		target.write ("#{infoTD.css("p")[1].text.squeeze(" ")}\n")
		target.write ("Address: #{infoTD.css("address").text.squeeze(" ")}\n")
		target.write ("\n")
	# 4 TD per location
end

for i in 1..totalLications / 2
	# Insert infoTD.css("h4").text.squeeze(" ") into places table
end



