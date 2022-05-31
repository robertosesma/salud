library(RMariaDB, lib.loc="/home/pi/R/arm-unknown-linux-gnueabihf-library/3.5")
library(lubridate, lib.loc="/home/pi/R/arm-unknown-linux-gnueabihf-library/3.5")

args = commandArgs(trailingOnly=TRUE)
user = args[1]
pswd = args[2]
ini = args[3]
fin = args[4]

con <- dbConnect(drv = RMariaDB::MariaDB(), dbname= "pa", host = "localhost", username = user, password = pswd)
query = paste("SELECT * FROM listado WHERE dia>='",ini,"' AND dia<='",fin,"'")
rs <- dbSendQuery(con, query)
data <- dbFetch(rs)
dbClearResult(rs)
dbDisconnect(con)

png(filename="/var/www/html/pa/public/evol_pa.png", width=590, height=460)

x <- data$dia
xmax = as.Date(x[which.max(x)])
xmax = ISOdate(year(xmax),month(xmax)+1,1)
xmin = as.Date(x[which.min(x)])
xmin = ISOdate(year(xmin),month(xmin),1)

plot(x=data$dia, y=data$PAS, pch=20, cex=1, col="black", ylim=c(70,170),
     yaxt="n", xaxt="n", xlab="", ylab="")
points(x=data$dia,y=data$PAD,pch=20,col='dodgerblue',cex=1)
abline(h=c(140,90),lty=2,col='red')
abline(h=c(120,80),lty=2)

ytick<-seq(70, 170, by=10)
axis(side=2, at=ytick, labels = ytick, las=1)
rug(x = seq(70, 170, by=2), ticksize = -0.02, side = 2)

if (difftime(xmax,xmin,units="days")>365) xtick<-seq(xmin,xmax,by="2 month")
if (difftime(xmax,xmin,units="days")<=365) xtick<-seq(xmin,xmax,by="1 month")
axis.Date(1, at=xtick, format="%m %y", las=2)

legend("topright", inset=.02, legend=c("PAS (mmHg)", "PAD (mmHg)"),
       col=c("black", "dodgerblue"), pch=20:20, cex=1, box.lty=0)

dev.off()

remove(data)
