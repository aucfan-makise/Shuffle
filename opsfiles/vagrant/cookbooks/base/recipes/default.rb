#
# Cookbook Name:: base
# Recipe:: default
#
# Copyright (c) 2015 The Authors, All Rights Reserved.
package "php"

package "httpd"
serivce "httpd" do
	action [:enable, :start]
end

template "/etc/httpd/conf/httpd.conf" do
	source "httpd.conf.erb"
end

execute "setenforce 0" do
	only_if "getenforce | grep -q Enforcing"
end

execute "Disable SELinux" do
	command "sed -ri 's/SELINUX=.*/SELINUX=disabled/' /etc/selinux/config"
	only_if "test -f /etc/selinux/config && ! grep -q 'SELINUX=disabled' /etc/selinux/config"
end

service "iptables" do
	action [:disable, :stop]
end
