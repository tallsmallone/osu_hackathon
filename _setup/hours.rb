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


sitePage = "http://food.osu.edu/hours/"
diningLocations = agent.get sitePage
hoursPage = Nokogiri::HTML(diningLocations.body)
infoTR = hoursPage.css("tr")
#puts infoTR.size
queue = Array.new
ids = Array.new
prevID = 0
for i in 1...infoTR.size
	infoTD = infoTR[i].css('td')
	name = infoTD[0]
	possInfo = name.css('p')
	possNotes = ""
	
	if possInfo.size > 0
		name = possInfo[0].text.gsub('É', 'E')
		if possInfo.size > 1
			possNotes = possInfo[1].text
		end
	else
		name = infoTD[0].text.gsub('É', 'E')
	end
	minToThur = infoTD[1].text
	fri = infoTD[2].text
	sat = infoTD[3].text
	sun = infoTD[4].text
	nameSize = ((name.length / 3) - 1)
	searchName = name[0..-nameSize].gsub("'", "''")  
	#puts name + "\n"
	puts searchName + "\n"
	rs = con.query("SELECT p.id AS id FROM places p WHERE p.name LIKE '#{searchName}%';")
  n_rows = rs.num_rows
  
  id = 0;
  if n_rows > 0
  	id = rs.fetch_row[0].to_i
  	
  	a = prevID + 1
  	while a < id && a != 86 && a != 129
  		con.query("insert into hours (id) values (#{a});")
  		a += 1
  	end
  	a -= 1
  	prevID = id
  	ids.push(id)
  	con.query("insert into hours (id, mon, tue, wed, thu, fri, sat, sun, notes) values (#{id}, '#{minToThur}', '#{minToThur}', '#{minToThur}', '#{minToThur}', '#{fri}', '#{sat}', '#{sun}', '#{possNotes}');")
  else
  	queueIndex = Array.new
  	queueIndex.push(minToThur)
  	queueIndex.push(fri)
  	queueIndex.push(sat)
  	queueIndex.push(sun)
  	queueIndex.push(possNotes)
  	queue.push(queueIndex)
  end
end

newID = ids.max
puts "Max: #{newID}"
newID += 1
puts "68 #{queue.size}"
while queue.size > 0
	index = queue.pop()
	monThu = index.pop()
	con.query("insert into hours (id, mon, tue, wed, thu, fri, sat, sun, notes) values (#{newID}, '#{monThu}', '#{monThu}', '#{monThu}', '#{monThu}', '#{index.pop()}', '#{index.pop()}', '#{index.pop()}', '#{index.pop()}');")
	newID += 1
end

rs = con.query("SELECT * FROM places;")
n_rows = rs.num_rows + 30

con.close
