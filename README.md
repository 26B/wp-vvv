# WP Seed

This project aims to allow a for a quick start of WordPress project with a configuration for VVV 2.0 and several boilerplate themes.

## Dependencies (Must Have Genesis)

This setup requires you to have Genesis main theme (the Genesis Framework) in order for its child-theme to work. You can get it here: http://my.studiopress.com/themes/genesis/

Note: Without this theme you can still take some things from the VVV configurations and Gulp setup in the child theme.

## Introduction

This guide assumes some basic familiarity with the command line, the Git version control system and Vagrant.

There are many graphical tools allowing you to achieve the same results as the ones described here, but a step-by-step guide for those is outside the scope of this document.

## Before you begin

This setup requires recent versions of both Vagrant and VirtualBox to be installed.

[Vagrant](http://www.vagrantup.com) is a "tool for building and distributing development environments". It works with [virtualization](http://en.wikipedia.org/wiki/X86_virtualization) software such as [VirtualBox](https://www.virtualbox.org/) to provide a virtual machine sandboxed from your local environment.

## Adding the Project to Your VVV Environment

This project should be added to the `www` directory in your VVV setup.  If you've followed the guide to installing VVV, the directory may be reached by typing `cd vagrant-local/www` in the command line interface.

Now clone the project into the `www` folder:

```
$ git clone git@github.com:xipasduarte/wp-seed.git wp-seed
```

This should create a new directory called `wp-seed` containing all the project files.

We're not done yet: VVV needs to setup and incorporate the project into its configuration. That is achieved by running:

```
$ vagrant provision
```

Take a break while VVV updates itself and builds the project for you. When done, a new site will become available at http://genesis.wordpress.dev/ and you may get to work.
