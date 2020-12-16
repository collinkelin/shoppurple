#!/bin/bash

path=$(pwd)

echo "[Unit]
Description=Orders Settlement
After=network.target
# After=syslog.target

[Service]
Type=simple
LimitNOFILE=65535
ExecStart=/usr/bin/php ${path}/think settlement
ExecReload=/bin/kill -USR1 \$MAINPID
Restart=always

[Install]
WantedBy=multi-user.target
# WantedBy=multi-user.target graphical.target" > /etc/systemd/system/orders-settlement.service

chmod 755 /etc/systemd/system/orders-settlement.service

# 创建软连接，加入守护进程列表
systemctl enable orders-settlement.service
# 重载守护进程配置
systemctl daemon-reload
# 加入自启动
systemctl enable orders-settlement.service
# 启动
systemctl start orders-settlement.service
# 重启
# systemctl restart orders-settlement.service
# 停止
# systemctl stop orders-settlement.service
# 查看状态
systemctl status orders-settlement.service
# 查看日志
# journalctl -f -u orders-settlement.service --since today
journalctl -n -f -u orders-settlement.service -n