# Check to determine whether we're on a windows or linux/os-x host,
# later on we use this to launch ansible in the supported way
# source: https://stackoverflow.com/questions/2108727/which-in-ruby-checking-if-program-exists-in-path-from-ruby
def which(cmd)
    exts = ENV['PATHEXT'] ? ENV['PATHEXT'].split(';') : ['']
    ENV['PATH'].split(File::PATH_SEPARATOR).each do |path|
        exts.each { |ext|
            exe = File.join(path, "#{cmd}#{ext}")
            return exe if File.executable? exe
        }
    end
    return nil
end

Vagrant.configure("2") do |config|
    # Cache packages and composer dependencies while vagrant-cachier plugin is present.
    # see http://fgrehm.viewdocs.io/vagrant-cachier/usage/
    if Vagrant.has_plugin?('vagrant-cachier')
        config.cache.scope = :box # enables caching for specific box (matching by name)
        config.cache.auto_detect = false
        config.cache.enable :apt
        config.cache.enable :npm

        # Cache the composer cache directory to host machine
        config.cache.enable :generic, cache_dir: '/home/vagrant/.composer/cache'
        # Use NFS on non-Windows hosts
        unless Vagrant::Util::Platform.windows?
            config.cache.synced_folder_opts = {type: 'nfs'}
        end
    end

    config.vm.box = "ubuntu/xenial64"
    config.vm.network :private_network, ip: "192.168.33.99"
    config.ssh.forward_agent = true

    config.vm.provider :virtualbox do |v|
        v.name = "workshop"
        v.memory = 2048
        v.cpus = 2
    end

    # If ansible is in your path it will provision from your HOST machine
    if which('ansible-playbook')
        config.vm.provision "ansible" do |ansible|
            ansible.playbook = "./.ansible/playbook.yml"
            ansible.inventory_path = "./.ansible/inventories/dev"
            ansible.limit = 'all'
        end
    # If ansible is not found in the path it will be instaled in the VM and provisioned from there
    else
        config.vm.provision :shell, path: "/var/www/workshop.vm/.ansible/apply.sh", args: ["default"]
    end

    config.vm.synced_folder "./", "/var/www/workshop.vm", nfs: true
end
