library(RMariaDB, lib.loc="/home/pi/R/arm-unknown-linux-gnueabihf-library/3.5")
library(lubridate, lib.loc="/home/pi/R/arm-unknown-linux-gnueabihf-library/3.5")

args = commandArgs(trailingOnly=TRUE)
user = args[1]
pswd = args[2]
ini = args[3]
fin = args[4]

con <- dbConnect(drv = RMariaDB::MariaDB(), dbname= "pa", host = "localhost", username = user, password = pswd)
query = paste("SELECT * FROM Peso WHERE Fecha>='",ini,"' AND Fecha<='",fin,"'")
rs <- dbSendQuery(con, query)
data <- dbFetch(rs)
dbClearResult(rs)
dbDisconnect(con)

png(filename="/var/www/html/pa/public/evol_peso.png", width=590, height=460)

y <- data$peso
ymax = floor(y[which.max(y)] + 1)
ymin = floor(y[which.min(y)] - 1)

x <- data$Fecha
xmax = as.Date(x[which.max(x)])
xmax = ISOdate(year(xmax),month(xmax)+1,1)
xmin = as.Date(x[which.min(x)])
xmin = ISOdate(year(xmin),month(xmin),1)

plot(x=x, y=y, ylim=c(ymin,ymax), yaxt="n", ylab="Peso (kg)", xaxt="n", xlab=" ")

ytick<-seq(ymin, ymax, by=2)
axis(side=2, at=ytick, labels = ytick, las=1)
rug(x = seq(ymin, ymax, by=1), ticksize = -0.02, side = 2)

if (difftime(xmax,xmin,units="days")>365) xtick<-seq(xmin,xmax,by="2 month")
if (difftime(xmax,xmin,units="days")<=365) xtick<-seq(xmin,xmax,by="1 month")
axis.Date(1, at=xtick, format="%m %y", las=2)

dev.off()

remove(data)
