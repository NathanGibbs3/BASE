#       
#
#       cd SPECS
#       rpmdev-bumpspec -u "John Doe <John's@email.address.net>" -c "Fixed this and that" base.spec 
#       rpmlint base.spec
#       rpmbuild --quiet -ba base.spec
#
#       cd ../RPMS/noarch
#       rpmlint base-cvs_20080621.noarch.rpm
#       rpmlint base-contrib-cvs_20080621.noarch.rpm
#       rpm --addsign base-cvs_20080621.noarch.rpm
#       rpm --addsign base-contrib-cvs_20080621.noarch.rpm
#       rpm --checksig base-cvs_20080621.noarch.rpm
#       rpm --checksig base-contrib-cvs_20080621.noarch.rpm
#       rpm -Uvh --test base-cvs_20080621.noarch.rpm
#       rpm -Uvh --test base-contrib-cvs_20080621.noarch.rpm
#
#       cd ../../SRPMS
#       rpmlint base-cvs_20080621.src.rpm
#       rpm --addsign base-cvs_20080621.src.rpm
#       rpm --checksig base-cvs_20080621.src.rpm
#       rpm -Uvh --test base-cvs_20080621.src.rpm
# 
#
# TODO:
# - adodb as an rpm with a version requirement? 
#   rpm -Uvh --test php-adodb-4.95-1.a.fc8.noarch.rpm 
#   error: php-adodb-4.95-1.a.fc8.noarch.rpm: headerRead failed: hdr blob(26277): BAD, read returned 18062
#   error: php-adodb-4.95-1.a.fc8.noarch.rpm cannot be installed
#
# - BASE does not really depend on httpd. So the "base.conf" should be
#   installed optionally
#
# - Should the perl scripts have their own target directory?
#
# - selinux policy module


############ Main package "base" ##################
Name: base
Version: 1.4.5
Release: 1
Summary: BASE - Basic Analysis and Security Engine

# Which categories for "Group:" are actually allowed?
# Cf. /usr/share/doc/rpm-4.4.2.3/GROUPS
Group: Applications/Internet
# tag Vendor is forbidden with Fedora; cf. wiki: Packaging/Guidelines#tags
Vendor: SecureIdeas 
License: GPLv2
URL: http://secureideas.sourceforge.net/
Source0: %{name}-%{version}.tar.gz
Patch0: base_maintenance.pl.patch
BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root-%(%{__id_u} -n)
BuildArch: noarch
# Explicit statements in "Requires:" are only necessary for version 
# requirements.  All of the other dependencies are done by rpm automatically.
Requires: php >= 4.0.4,  php-pear >= 1.5.3, php-pear-Image-Color >= 1.0.2, php-pear-Image-Canvas >= 0.3.1 , php-pear-Image-Graph >= 0.7.2, php-pear-Numbers-Roman >= 1.0.2, php-pear-Mail, php-pear-Mail-Mime, httpd 
Requires(post): policycoreutils 
Requires(postun): policycoreutils
# , php-gd, php-pear-Image-Graph-roman, php-pear-Numbers-Words, 
# php-pear-Image-Graph-words, php-pear-Mail, php-pear-Auth-SASL, 
# php-pear-Net-Socket, php-pear-Net-SMTP, php-pear-Mail-mimeDecode, 
# php-pear-Mail-Mime
#  However, omitting httpd is very much doubtful, as we provide 
#  /etc/httpd/conf.d/base.conf
# And the mail feature is no recognized by rpmbuild, either: It requires
# php-pear-Mail and php-pear-Mail-Mime (plus dependencies)

%description
BASE is the Basic Analysis and Security Engine.  It is based on the code 
from the Analysis Console for Intrusion Databases (ACID) project.  This 
application provides a web front-end to query and analyze the alerts 
coming from a SNORT IDS system.

BASE is a web interface to perform analysis of intrusions that SNORT 
has detected on your network.  It uses a user authentication and 
role-based system, so that you as the security admin can decide 
which and how much information each user can see.  It also has a 
simple to use, web-based setup program for people who feel not 
comfortable with editing files directly.

BASE is supported by a group of volunteers.  They are available to answer 
any questions you may have or help you out in setting up your system. 
They are also skilled in intrusion detection systems and make use of 
that knowledge in the development of BASE. You can contact them 
through the website http://secureideas.sourceforge.net/ or by 
emailing them at base@secureideas.net



########### sub package "base-contrib" #############
%package contrib
Summary: BASE contrib: Additional perl scripts for importing snort unified log files
Group: Applications/Internet
License: GPLv2
BuildArch: noarch
%description contrib
This perl module makes the handling of snort unified log files (version 1)
easy.  It reads in snort unified log files and offers different output 
possibilities:
        - csv file
        - syslog
        - xml file
        - mysql database



%prep
%setup -q
%define _worldmap_target_dir usr/share/pear/Image/Graph/Images/Maps
# Why /usr/share/base-x.y.z rather than /var/www/html/base-x.y.z ?
# Because of fedora packaging guidelines at: 
# http://fedoraproject.org/wiki/Packaging/Guidelines
# "Web Applications
#
#   Web applications packaged in Fedora should put their content into
#   /usr/share/%{name} and NOT into /var/www/. This is done because:
#     * /var is supposed to contain variable data files and logs.
#       /usr/share is much more appropriate for this.
#     * Many users already have content in /var/www, and we do not want
#       any Fedora package to step on top of that.
#     * /var/www is no longer specified by the Filesystem Hierarchy
#       Standard"
## Cf. Filesystem Hierarchy Standard, at: http://www.pathname.com/fhs/
%define _php_files_target_dir usr/share/base-%{version}
%define _perl_files_target_dir  %{_php_files_target_dir}
%define _base_conf_header0 "Alias /base \\"/%{_php_files_target_dir}\\""
%define _base_conf_header1 "Alias /base-%{version} \\"/%{_php_files_target_dir}\\""
%define _base_conf_header2 "<Directory \\"/%{_php_files_target_dir}\\">"


%patch0 -p0 


%build
# Keep it empty as it is


%install
%__rm -rf %{buildroot}


# At first, establish all the directories
%__mkdir_p -m 0755 %{buildroot}/%{_php_files_target_dir}
%__mkdir_p -m 0755 %{buildroot}/%{_perl_files_target_dir}
%__mkdir_p -m 0755 %{buildroot}/%{_worldmap_target_dir}
#%__mkdir_p -m 0755 %{buildroot}/etc/httpd/conf.d/
%__mkdir_p -m 0755 %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf
%__mkdir_p -m 0755 %{buildroot}%{_docdir}/%{name}-%{version}
%__mkdir_p -m 0755 %{buildroot}%{_docdir}/%{name}-%{version}/contrib

# Install the sub directories INCLUDING the files inside
%__cp -dpR admin %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR contrib %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR help %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR images %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR includes %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR languages %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR rpm %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR scripts %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR setup %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR sql %{buildroot}/%{_php_files_target_dir}/
%__cp -dpR styles %{buildroot}/%{_php_files_target_dir}/

# Install the files in the top level directory
%__install -m 0644 index.php %{buildroot}/%{_php_files_target_dir}/
%__install -m 0644 base* %{buildroot}/%{_php_files_target_dir}/

# These two files have to go in a PEAR specific direction
%__install -m 0644 world_map6.txt %{buildroot}/%{_worldmap_target_dir}/
%__install -m 0644 world_map6.png %{buildroot}/%{_worldmap_target_dir}/

# The docs go to a doc-specific location
# And this particular document HAS TO be enclosed by quotation marks
# because of the multibyte inside.
install -m 0644 "docs/contrib/Snort, Apache, MYSQL, PHP, and BASE instalación en Slackware.pdf" %{buildroot}%{_docdir}/%{name}-%{version}/contrib/
cp -dpR docs/* %{buildroot}%{_docdir}/%{name}-%{version}/

# and the base.conf for apache still has to be generated
#if test -r %{buildroot}/etc/httpd/conf.d/base.conf; then
if test -r %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf; then
  #%__mv -u %{buildroot}/etc/httpd/conf.d/base.conf %{buildroot}/etc/httpd/conf.d/base.conf.rpmsave || :
  %__mv -u %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf.rpmsave || :
fi

#echo %{_base_conf_header0} > %{buildroot}/etc/httpd/conf.d/base.conf
echo %{_base_conf_header0} > %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf
#echo %{_base_conf_header1} >> %{buildroot}/etc/httpd/conf.d/base.conf
echo %{_base_conf_header1} >> %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf
#echo %{_base_conf_header2} >> %{buildroot}/etc/httpd/conf.d/base.conf
echo %{_base_conf_header2} >> %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf

# Yes, enforcing SSL is commented out deliberately.  It's just an
# offer to the user.
#cat >> %{buildroot}/etc/httpd/conf.d/base.conf << EOT
cat >> %{buildroot}/%{_sysconfdir}/httpd/conf.d/%{name}.conf << EOT
        ##### What is allowed in .htaccess? ######
        AllowOverride AuthConfig Limit
        ##### Which hosts are allowed to access BASE? ######
        Order deny,allow
        Deny from all
        Allow from 127.0.0.1
        ###### Enforce ssl by rewriting the URL: ########
#        SSLOptions      +FakeBasicAuth +StrictRequire
#        SSLVerifyClient optional
#        SSLVerifyDepth  1
#        SSLRequire      %{SSL_CIPHER_USEKEYSIZE} >= 128
#        RewriteEngine   on
#        RewriteCond     %{SERVER_PORT}     !^443$
#        RewriteRule     (.*) https://%{HTTP_HOST}%{REQUEST_URI}
</Directory>
EOT


%post
# install $1 == 1
# upgrade $1 == 2
if [ $1 -gt 0 ]; then
  # http://fedoraproject.org/wiki/PackagingDrafts/SELinux?action=fullsearch&value=linkto%3A%22PackagingDrafts/SELinux%22&context=180
  # file:///usr/local/doc/selinux/selinux_adding_to_a_package.html
  # Set SELinux file context in the policy
  # Apache must be allowed to read and execute the files.
  # Apache must be allowed to write base_conf.php.
  # Both semanage and restorecon require full paths.
  %define SEMANAGE `which semanage`
  %define RESTORECON `which restorecon`
  %define SETSEBOOL `which setsebool`
  if test -n "%{SEMANAGE}" -a -x "%{SEMANAGE}"; then
    if test -n "%{RESTORECON}" -a -x "%{RESTORECON}"; then
      %{SEMANAGE} fcontext --add -t httpd_user_content_t '/%{_php_files_target_dir}/.*' > /dev/null 2> /dev/null || %{SEMANAGE} fcontext --modify -t httpd_user_content_t '/%{_php_files_target_dir}/.*' > /dev/null 2> /dev/null || semanage fcontext --add -t httpd_sys_content_t '/%{_php_files_target_dir}' > /dev/null 2> /dev/null || : 

      # The top directory must be writable for base_conf.php.
      %{SEMANAGE} fcontext --add -t httpd_user_content_rw_t '/%{_php_files_target_dir}' > /dev/null 2> /dev/null || %{SEMANAGE} fcontext --modify -t httpd_user_content_rw_t '/%{_php_files_target_dir}' > /dev/null 2>/dev/null > /dev/null 2> /dev/null || : 

      # Actually change the context 
      %{RESTORECON} -R '/%{_php_files_target_dir}' > /dev/null 2> /dev/null || :
      if test -n "%{SETSEBOOL}"; then
        %{SETSEBOOL} -P httpd_can_network_connect_db=1
      else
        echo "WARNING: setsebool could NOT be found. For BASE to work properly"
        echo "         apache must be allowed to connect to databases."
        echo "         You can achieve this by:"
        echo
        echo "         setsebool -P httpd_can_network_connect_db=1"
        echo
      fi
    else
      echo "WARNING: semanage could be found, but restorecon could NOT be found. Omitting selinux related steps."
    fi
  else
    echo "WARNING: semanage could NOT be found. Omitting selinux related steps."
    echo  "PATH = \"$PATH\""
    echo  "SEMANAGE = \"%SEMANAGE\""
  fi

  # Make base.conf known to an already running apache only.
  rv=`/sbin/pidof httpd 2> /dev/null`
  if test $? -eq 0; then
    /sbin/service httpd reload > /dev/null 2>&1 || :
  fi
fi



%preun
# This should be removed, as well.  Otherwise /usr/share/base-x.y.z would
# remain  untouched, although the user has decided to remove the package.
%__rm -f "/%{_php_files_target_dir}/base_conf.php" || :
%__rm -f "/%{_php_files_target_dir}/base_conf.php~" || :



%postun
# install: $1 is not available
# upgrade: $1 == 1

# Inform apache about the removal of /etc/httpd/conf.d/base.conf
rv=`/sbin/pidof httpd 2> /dev/null`
if test $? -eq 0; then
  /sbin/service httpd reload > /dev/null 2>&1 || :
fi




%clean
%__rm -rf %{buildroot}



################# filelist... #####################
##### all the directories without CVS and SnortUnified #####
# find /var/www/html/base-php4 -type d ! -iname "CVS" ! -iwholename "*SnortUnified*" | sed 's/^/%dir %attr(0755,apache,apache) /; s/\/var\/www\/html\/base-php4/\/%{_php_files_target_dir}/' | sort -d | uniq > /tmp/filelist
# echo "\n" >> /tmp/filelist
# 
##### all the php files ##### 
# find /var/www/html/base-php4 -type f ! -iwholename "*CVS*" ! -iwholename "*/SnortUnified/*" ! -iwholename "*/docs/*" ! -iwholename "*/scripts/*" ! -iname "*.orig" ! -iname "*~" ! -iname "*.old" ! -iname "*.conf.php" | sed 's/^/%attr(0644,apache,apache) /; s/\/var\/www\/html\/base-php4/\/%{_php_files_target_dir}/' | sort -d >> /tmp/filelist
# echo -e "\n\n" >> /tmp/filelist
# 
##### base_maintenance.pl (could be installed anywhere else - to any other
##### location than %{_php_files_target_dir} #####
# find /var/www/html/base-php4/scripts/ ! -iwholename "*CVS*" ! -iname "*.orig" ! -iname "*~" ! -iname "*.old" |  sed 's/^/%attr(0755,apache,apache) /; s/\/var\/www\/html\/base-php4/\/%{_php_files_target_dir}/' | sort -d | uniq >> /tmp/filelist
# echo -e "\n\n" >> /tmp/filelist
#
##### the docs subdirectory goes to sth like /usr/share/doc/base-x.y.z #####
# reset; find /var/www/html/base-php4/docs/ -type d ! -iwholename "*CVS*" ! -iname "*.orig" ! -iname "*~" ! -iname "*.old" |  sed 's/^/%dir %attr(0755,root,root) /; s/\/var\/www\/html\/base-php4\/docs/\/%{_docdir}\/%{name}-%{version}/' | sort -d | uniq
# echo -e "\n\n" >> /tmp/filelist
#
#### the files in the docs subdirectory go to sth like /usr/share/doc/base-x.y.z #####
# find /var/www/html/base-php4/docs/ -type f ! -iwholename "*CVS*" ! -iname "*.orig" ! -iname "*~" ! -iname "*.old" |  sed 's/^/%doc %attr(0644,root,root) /; s/\/var\/www\/html\/base-php4\/docs/\/%{_docdir}\/%{name}-%{version}/' | sort -d | uniq >> /tmp/filelist
# echo -e "\n\n" >> /tmp/filelist
#
##### the world_map.(txt|png) go to a PEAR specific location #####
# find /var/www/html/base-php4 -type f -iname "*world_map*" ! -iwholename "*CVS*" ! -iname "*.orig" ! -iname "*~" ! -iname "*.old" |  sed 's/^/%attr(0644,root,root) /; s/\/var\/www\/html\/base-php4/\/%{_worldmap_target_dir}/' | sort -d | uniq >> /tmp/filelist
# echo >> /tmp/filelist
#
##### base.conf for apache #####
# echo "%config(noreplace) %attr(0644,root,root) /etc/httpd/conf.d/base.conf" >> tmp/filelist
# 
##########################################
# Attention with the multibyte in docs/contrib/Snort, Apache, MYSQL, PHP, and BASE...:  This particular filename HAS TO be enclosed by quotation marks.
# because of the multibyte inside.
#
############### filelist of package "base" #####################
%files
%defattr(0644,apache,apache)
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/admin
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/contrib
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/help
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/images
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/includes
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/languages
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/rpm
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/scripts
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/setup
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/sql
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/styles
%attr(0644,apache,apache) /%{_php_files_target_dir}/admin/base_roleadmin.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/admin/base_useradmin.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/admin/index.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_ag_common.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_ag_main.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_common.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_conf.php.dist
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_db_common.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_db_setup.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_denied.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_footer.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_graph_common.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_graph_display.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_graph_form.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_graph_main.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_hdr1.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_hdr2.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_local_rules.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_logout.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_mac_prefixes.map
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_main.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_maintenance.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_payload.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_qry_alert.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_qry_common.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_qry_form.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_qry_main.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_qry_sqlcalls.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_alerts.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_class.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_common.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_ipaddr.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_iplink.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_ports.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_sensor.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_time.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_stat_uaddr.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/base_user.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/contrib/barnyard-base.patch
%attr(0644,apache,apache) /%{_php_files_target_dir}/contrib/base-rss.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/contrib/custom_base_footer.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/help/base_app_faq.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/help/base_help.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/help/base_setup_help.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/images/button_delete.png
%attr(0644,apache,apache) /%{_php_files_target_dir}/images/button_edit.png
%attr(0644,apache,apache) /%{_php_files_target_dir}/images/button_exclamation.png
%attr(0644,apache,apache) /%{_php_files_target_dir}/images/greencheck.gif
%attr(0644,apache,apache) /%{_php_files_target_dir}/images/greencheck.png
%attr(0644,apache,apache) /%{_php_files_target_dir}/images/redcheck.gif
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_action.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_auth.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_cache.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_capabilities.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_constants.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_db.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_include.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_iso3166.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_log_error.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_log_timing.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_net.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_output_html.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_output_query.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_setup.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_signature.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_state_citems.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_state_common.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_state_criteria.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_state_query.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_template.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/includes/base_user.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/index.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/chinese.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/czech.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/danish.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/english.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/finnish.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/french.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/german.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/index.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/indonesian.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/italian.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/japanese.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/norwegian.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/polish.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/portuguese.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/portuguese-PT.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/russian.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/simplified_chinese.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/spanish.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/swedish.lang.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/languages/turkish.lang.php
%attr(0755,apache,apache) /%{_php_files_target_dir}/scripts/base_maintenance.pl
%attr(0644,apache,apache) /%{_php_files_target_dir}/rpm/base_maintenance.pl.patch
%attr(0644,apache,apache) /%{_php_files_target_dir}/rpm/base.spec
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/base_conf_contents.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/index.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/setup1.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/setup2.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/setup3.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/setup4.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/setup5.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/setup/setup_db.inc.php
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/acid2base_tbls_mssql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/acid2base_tbls_mysql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/acid2base_tbls_pgsql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/create_base_tbls_mssql_extra.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/create_base_tbls_mssql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/create_base_tbls_mysql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/create_base_tbls_oracle.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/create_base_tbls_pgsql_extra.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/create_base_tbls_pgsql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/sql/upgrade_0.9.x_to_1.0-mysql.sql
%attr(0644,apache,apache) /%{_php_files_target_dir}/styles/acid_style.css
%attr(0644,apache,apache) /%{_php_files_target_dir}/styles/base_black_style.css
%attr(0644,apache,apache) /%{_php_files_target_dir}/styles/base_red_style.css
%attr(0644,apache,apache) /%{_php_files_target_dir}/styles/base_style.css

%dir %attr(0755,root,root) %{_docdir}/%{name}-%{version}/
%dir %attr(0755,root,root) %{_docdir}/%{name}-%{version}/contrib
%doc %attr(0644,root,root) "%{_docdir}/%{name}-%{version}/contrib/Snort, Apache, MYSQL, PHP, and BASE instalación en Slackware.pdf"
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/base_faq.rtf
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/CHANGELOG
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/CREDITS
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/GPL
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/INSTALL
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/INSTALL.rtf
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/README
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/README.country_support
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/README.email_quirks
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/README.graph_alert_data
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/README.mssql
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/README.rpm
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/TODO
%doc %attr(0644,root,root) %{_docdir}/%{name}-%{version}/UPGRADE

%attr(0644,root,root) /%{_worldmap_target_dir}/world_map6.png
%attr(0644,root,root) /%{_worldmap_target_dir}/world_map6.txt

#%config(noreplace) %attr(0644,root,root) /etc/httpd/conf.d/base.conf
%config(noreplace) %attr(0644,root,root) /%{_sysconfdir}/httpd/conf.d/%{name}.conf




################ filelist of subpackage "base-contrib" ###############
%files contrib
%defattr(0644,apache,apache)
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}
%dir %attr(0755,apache,apache) /%{_php_files_target_dir}/contrib
%dir %attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified
%dir %attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/SnortUnified
%doc %attr(0644,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/LICENSE
%doc %attr(0644,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/README
%attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/pcaptodb.pl
%attr(0644,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/SnortUnified/Database.pm
%attr(0644,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/SnortUnified.pm
%attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/uf_csv.pl
%attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/ufdbtest.pl
%attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/uf_syslog.pl
%attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/uftester.pl
%attr(0755,apache,apache) /%{_perl_files_target_dir}/contrib/SnortUnified/uf_xml.pl




%changelog
* Fri Nov 20 2009 Juergen Leising <jleising@users.sourceforge.net> - 1.4.5-1
Security fixes and a fix regarding the archive function.

* Mon Aug 10 2009 Juergen Leising <jleising@users.sourceforge.net> - 1.4.4-2
Fixed Cross site scripting and local file include in base_local_rules.php

* Sat Jul 25 2009 Juergen Leising <jleising@users.sourceforge.net> - 1.4.4-1
Fix for an SQL injection flaw reported by Peter Österberg

* Mon Jun 01 2009 Juergen Leising <jleising@users.sourceforge.net> - 1.4.3.1-1
Some more XSS and CSRF flaws have been fixed.

* Fri May 29 2009 Juergen Leising <jleising@users.sourceforge.net> - 1.4.3-1
Several XSS and CSRF bugs have been fixed.

* Sun Apr 05 2009 Juergen Leising <jleising@users.sourceforge.net> - 1.4.2-1
Preparing for release 1.4.2

* Fri Aug 01 2008 Juergen Leising <jleising@users.sourceforge.net> - 1.4.1-1
Preparing for release 1.4.1.

* Wed Jul 02 2008 Juergen Leising <jleising@users.sourceforge.net> - cvs_20080702-1
SELinux related install and uninstall steps depend now on whether semanage and restorecon can be found at all.

* Mon Jun 30 2008 Juergen Leising <jleising@users.sourceforge.net> - cvs_20080630-1
New coordinates file worldmap6.txt.

* Thu Jun 26 2008 Juergen Leising <jleising@users.sourceforge.net> - cvs_20080626-1
Several changes to contrib/SnortUnified have been made. The base-contrib rpm is now installable under fedora 9.

* Sat Jun 21 2008 Juergen Leising <jleising@users.sf.net> - cvs_20080621-1
Several bug fixes; SnortUnified plugin now works with perl-5.8.8 (fedora 7)

* Tue Jun 17 2008 Juergen Leising <jleising@users.sourcefoge.net> - cvs_20080617-1
Added fall-back file context httpd_sys_content_t, if httpd_user_content_rw_t is an unknown type; fixed attributes of subdirectory rpm

* Mon Jun 16 2008 Juergen Leising <jleising@users.sourcefoge.net> - cvs_20080616-1
Added base.spec, base_maintenance.pl.patch and README.rpm to CVS tree

* Mon Jun 16 2008 Juergen Leising <jleising@users.sourcefoge.net> - cvs_20080613-1
Snapshot from CVS version as of June, 16th, 2008

* Wed Jun 11 2008 Juergen Leising <jleising@users.sourcefoge.net> - 1.4.0jl1-1
- Completely rewritten version of base.spec for BASE-1.4.1 (lara).  
  Partly based on a spec-file by Alejandro Flores <alejandro.flores@triforsec.com.br> 
  for some versions of base-1.2.x.



