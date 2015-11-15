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
tagsHTML = ""
q  = 1

	nameArray = Array.new
	linkArray = Array.new
	phoneArray = Array.new
	addressArray = Array.new
	tagsArray = Array.new
	typeArray = Array.new
while j <= 12
	puts "Page #{j}"
	sitePage = "https://buckid.osu.edu/merchants/list?TextContains=&LocationType=Restaurant&pagenumber=" + "#{j}"
	diningLocations = agent.get sitePage
	campusPage = Nokogiri::HTML(diningLocations.body)
	
	oddItems = campusPage.css("div[class='item-odd']")
	evenItems = campusPage.css("div[class='item-even']")
	totalLocations = oddItems.size + evenItems.size
	
=begin
	For tags:
	1 - Coffee Shop
	2 - Sandwiches/Deli
	3 - Hamburgers/American
	4 - Ice Cream/Desserts
	5 - Pizza/Pasta
	6 - Ethnic Foods
	7 - Convenience/Pharmacy
	8 - Other
=end

	for i in 1..totalLocations / 2
		infoTD = oddItems[i - 1]
		type = ""
		
		q += 1
		linkArray.push("https://buckid.osu.edu/" + "#{infoTD.css("a")[0]["href"]}\n")
		puts "LINK: #{linkArray[i]}\n"
		tags = Array.new
		for k in 0...infoTD.css("p").size
			tagsHTML += "#{infoTD.css("p")[k].text.split.join(' ').gsub("Location Type: ", "").gsub("Food Type:", "")}\n"
		end
		
		z = 0;
		individualType = Array.new
		individualType.push(1)
		icons = infoTD.css("li")
		if icons.size > 0
			while z < icons.size
				if icons[0].text.include? "Accepts Meal Plan"
					individualType.push(2)
				end
				z += 1
			end
		end
		individualType = individualType.join(", ")
		typeArray.push(individualType)
		
		if tagsHTML.include? "Coffee Shop"
			tags.push(1)
		end
		if tagsHTML.include? "Sandwiches/Deli"
			tags.push(2)
		end
		if tagsHTML.include? "Hamburgers/American"
			tags.push(3)
		end
		if tagsHTML.include? "Ice Cream/Desserts"
			tags.push(4)
		end
		if tagsHTML.include? "Pizza/Pasta"
			tags.push(5)
		end
		if tagsHTML.include? "Ethnic Foods"
			tags.push(6)
		end
		if tagsHTML.include? "Convenience/Pharmacy"
			tags.push(7)
		end
		if tagsHTML.include? "Other"
			tags.push(8)
		end
		tags = tags.join(", ")
		tagsArray.push(tags)
		# Get tags based on food type
		
		address = infoTD.css("address").text.split.join(' ')
		# Get address of location
		
		phone = address.split(//).last(13).join
		# Get phone number
		if !phone.include? "("
			phone = ""
		else
			address = address[0..-14]
		end
		
		phoneArray.push(phone)
		addressArray.push(address)
		tagsHTML = ""
		infoTD = evenItems[i - 1]
		#name = "#{infoTD.css("h4").text.split.join(' ').gsub("'", "''")}\n"
		q += 1
		linkArray.push("https://buckid.osu.edu" + "#{infoTD.css("a")[0]["href"]}\n")
		
		tags = Array.new
		for k in 0...infoTD.css("p").size
			tagsHTML += "#{infoTD.css("p")[k].text.split.join(' ').gsub("Location Type: ", "").gsub("Food Type:", "")}\n"
		end
		
		z = 0;
		individualType = Array.new
		individualType.push(1)
		icons = infoTD.css("li")
		if icons.size > 0
			while z < icons.size
				if icons[0].text.include? "Accepts Meal Plan"
					individualType.push(2)
				end
				z += 1
			end
		end
		individualType = individualType.join(", ")
		typeArray.push(individualType)
		
		if tagsHTML.include? "Coffee Shop"
			tags.push(1)
		end
		if tagsHTML.include? "Sandwiches/Deli"
			tags.push(2)
		end
		if tagsHTML.include? "Hamburgers/American"
			tags.push(3)
		end
		if tagsHTML.include? "Ice Cream/Desserts"
			tags.push(4)
		end
		if tagsHTML.include? "Pizza/Pasta"
			tags.push(5)
		end
		if tagsHTML.include? "Ethnic Foods"
			tags.push(6)
		end
		if tagsHTML.include? "Convenience/Pharmacy"
			tags.push(7)
		end
		if tagsHTML.include? "Other"
			tags.push(8)
		end
		tags = tags.join(", ")
		tagsArray.push(tags)
		# Get tags based on food type
		
		address = infoTD.css("address").text.split.join(' ')
		# Get address of location
		
		phone = address.split(//).last(13).join.gsub(")", ") ")
		# Get phone number
		if !phone.include? "("
			phone = ""
		else
			address = address[0..-14]
		end
		
		phoneArray.push(phone)
		addressArray.push(address)
		tagsHTML = ""
	#con.query("insert into info (id, website, menu, phone, location, geo, type, tags) values (#{q}, '#{linkArray[q - 1]}', '', '#{phoneArray[q - 1]}', '#{addressArray[q - 1]}', '', '', '#{tagsArray[q - 1]}');")
	end
	j += 1
end

puts "Phonesize #{phoneArray.size}"
for i in 1..phoneArray.size
	con.query("insert into info (id, website, menu, phone, location, geo, types, tags) values (#{i}, '#{linkArray[i - 1]}', '', '#{phoneArray[i - 1]}', '#{addressArray[i - 1]}', '', '#{typeArray[i - 1]}', '#{tagsArray[i - 1]}');")
	puts "#{i}\n"
end

con.close
