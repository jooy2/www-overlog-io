#!/bin/bash

hostname=52.78.192.102
tempfilename=logtemp
savedir=/home/nginx/www/logs

echo "LINUX" > $savedir/$hostname/$tempfilename
hostname | cat >> $savedir/$hostname/$tempfilename

echo "==cpu values==" >> $savedir/$hostname/$tempfilename
top -b -n 1 | head -n3 | tail -n1 | awk -F' ' '{if($1!~/#/) print $3$2";"$5$4";"$9$8}' | sed 's/,//' | sed 's/,//'| sed 's/,//' | sed 's/;/,/' | sed 's/;/,/' | sed 's/;/,/' | cat >> $savedir/$hostname/$tempfilename

echo "==cpu top5==" >> $savedir/$hostname/$tempfilename
ps aux --sort=-pcpu | sed '1d' | head -n5 | sed 's/  */ /' | awk -F' ' '{if($1!~/#/) print "**"$3","$11$12$13$14$15$16$17$18$19$20$21$22$23$24$25$26$27$28$29$30}' | cat >> $savedir/$hostname/$tempfilename

echo "==mem values==" >> $savedir/$hostname/$tempfilename
top -b -n 1 | head -n4 | tail -n1 | awk -F' ' '{if($1!~/#/) print $5$4";"$7$6";"$9$8";"$11$10}' | sed 's/,//'| sed 's/,//' | sed 's/,//'| sed 's/,//' | sed 's/;/,/' | sed 's/;/,/' | sed 's/;/,/' | cat >> $savedir/$hostname/$tempfilename

echo "==swap values==" >> $savedir/$hostname/$tempfilename
top -b -n 1 | head -n5 | tail -n1 | awk -F' ' '{if($1!~/#/) print $4$3";"$6$5";"$8$7";"$10" "$11$9}' | sed 's/,//' | sed 's/,//'| sed 's/,//' | sed 's/;/,/' | sed 's/;/,/' | sed 's/;/,/' | cat >> $savedir/$hostname/$tempfilename

echo "==mem top5==" >> $savedir/$hostname/$tempfilename
ps aux --sort=-pmem | sed '1d' | head -n5 | sed 's/  */ /' | awk -F' ' '{if($1!~/#/) print "**"$4","$11$12$13$14$15$16$17$18$19$20$21$22$23$24$25$26$27$28$29$30}' | cat >> $savedir/$hostname/$tempfilename

echo "==network traffic==" >> $savedir/$hostname/$tempfilename
ip -s link show dev ens33 | tail -n4 | head -n2 |tail -n1 | awk -F' ' '{if($1!~/ens/) print "RXbyte"$1",RXpackets"$2}' | cat >> $savedir/$hostname/$tempfilename
ip -s link show dev ens33 | tail -n1 | awk -F' ' '{if($1!~/ens/) print "TXbyte"$1",TXpackets"$2}' | cat >> $savedir/$hostname/$tempfilename

echo "==disk total==" >> $savedir/$hostname/$tempfilename
df --total | tail -n1 | awk -F' ' '{if($1!~/#/) print "1k-block"$2",us"$3}' | cat >> $savedir/$hostname/$tempfilename

echo "==disk other==" >> $savedir/$hostname/$tempfilename
df | awk -F' ' '{if($1!~/#/) print $6",1k-block"$2",us"$3}' | sed '1d' | cat >> $savedir/$hostname/$tempfilename

mv $savedir/$hostname/$tempfilename $savedir/$hostname/$(date +%Y%m%d%H%M%S).txt
