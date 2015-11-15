# Mechanize gets the website and transforms into HTML file.
require 'mechanize'
# Nokogiri gets the website data that could be read later on.
require 'nokogiri'
# MySQL gem
require 'mysql'
require 'inifile'

agent = Mechanize.new
agent.open_timeout = 60
agent.read_timeout = 60
agent.user_agent_alias = "Mac Safari" 
# Initialize new Mechanize agent


puts "Connected :)\n"
target = open('locations.txt', 'w')
j = 1

q  = 1
while j <= 12
		htmlP = "https://buckid.osu.edu/merchants/list?TextContains=&LocationType=Restaurant&pagenumber=" + "#{j}"
		diningLocations = agent.get htmlP
		campusPage = Nokogiri::HTML(diningLocations.body)
		# Get HTML page containing dining locations
	
	oddItems = campusPage.css("div[class='item-odd']")
	evenItems = campusPage.css("div[class='item-even']")
	totalLocations = oddItems.size + evenItems.size
	nameArray = Array.new
	for i in 1..totalLocations / 2
		infoTD = oddItems[i - 1]
		name = "#{infoTD.css("h4").text.split.join(' ').gsub("'", "''")}"
		con.query("insert into places (id, name) values (#{q}, '#{name}');")
		q += 1
		#target.write ("#{infoTD.css("h4")[0]['href'].text}\n")
		for k in 0...infoTD.css("p").size
			target.write ("#{infoTD.css("p")[k].text.split.join(' ').gsub("Location Type: ", "").gsub("Food Type:", "")}\n")
		end
		target.write ("#{infoTD.css("address").text.split.join(' ')}\n")
		target.write ("\n")

		infoTD = evenItems[i - 1]
		name = "#{infoTD.css("h4").text.split.join(' ').gsub("'", "''")}"
		con.query("insert into places (id, name) values (#{q}, '#{name}');")
		q += 1
		#target.write ("#{infoTD.css("h4").css("a").text.split.join(' ')}\n")
		#target.write ("#{infoTD.css("h4")[0]['href'].text}\n")
		for k in 0...infoTD.css("p").size
			target.write ("#{infoTD.css("p")[k].text.split.join(' ').gsub("Location Type: ", "").gsub("Food Type:", "")}\n")
		end
		address = infoTD.css('address').text.split.join(' ')
		
		puts address
		target.write ("\n")
		# 4 TD per location
		# Page 1
	end
	j += 1
end
for i in 1..totalLocations / 2
	# Insert infoTD.css("h4").text.squeeze(" ") into places table
end

con.close
