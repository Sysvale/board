variable "do_token" {}

provider "digitalocean" {
  token = var.do_token
}

data "digitalocean_droplet" "app_php7_1" {
  name = "app-php7-1"
}

data "digitalocean_domain" "cidadesaudavel" {
  name = "cidadesaudavel.com"
}

variable "workspace_to_record_dns_name" {
  type = map

  default = {
    development = "development.board"
    staging     = "alpha.board"
    production  = "board"
  }
}

variable "workspace_to_record_dns_alias" {
  type = map

  default = {
    development = "www.development.board"
    staging     = "www.alpha.board"
    production  = "www.board"
  }
}

locals {
  dns_name  = lookup(var.workspace_to_record_dns_name, terraform.workspace)
  dns_alias = lookup(var.workspace_to_record_dns_alias, terraform.workspace)
}

resource "digitalocean_record" "dns_name" {
  domain = data.digitalocean_domain.cidadesaudavel.name
  type   = "A"
  name   = local.dns_name
  value  = data.digitalocean_droplet.app_php7_1.ipv4_address
  ttl    = 3600
}

resource "digitalocean_record" "dns_alias" {
  domain = data.digitalocean_domain.cidadesaudavel.name
  type   = "CNAME"
  name   = local.dns_alias
  value  = "${digitalocean_record.dns_name.fqdn}."
  ttl    = 43200
}

resource "local_file" "ansible-inventory" {
  content  = "${data.digitalocean_droplet.app_php7_1.ipv4_address} ansible_port=8008"
  filename = "../configuration/inventory-${terraform.workspace}"
}
