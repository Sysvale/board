#!/bin/bash

EXIT_FAILURE=1

set -e

help() {
  echo 'Usage: deploy.bash [OPTIONS]... [-e|--environment]=ENVIRONMENT'
  echo 'Deploys insider application to a given environment'
  echo
  echo 'The command support the following options:'
  echo
  echo '    -e=, --environment=ENVIRONMENT      the environment to deploy to'
  echo '    -u=, --user=                        the user used to ssh into the machine'
  echo '    -u=, --user=                        the user used to ssh into the machine'
  echo '    -s=, --secret-file=                 file containing the vault secret key for sensitive files decription'
  echo '    -p=, --sudo-password=               the sudo password of the user specifyed'
  echo
  echo 'ENVIRONMENT := staging|production'
  echo
  echo 'It is possible to export the folowing env variables, which replace the options if they are not informed:'
  echo 'DEPLOY_USER for the user option'
  echo 'DEPLOY_USER_SUDO_PASS for the sudo-password option'
  echo 'DEPLOY_SECRET_FILE for the secret-file option'
  echo
  echo 'You also MUST setup the TF_VAR_do_token env variable witha valid digital ocean access token'
  echo
}

USER="$DEPLOY_USER"
SECRET_FILE="$DEPLOY_SECRET_FILE"
USER_SUDO_PASS="$DEPLOY_USER_SUDO_PASS"

for i in "$@"
do
case $i in
    -e=*|--environment=*)
    ENVIRONMENT="${i#*=}"
    shift # past argument=value
    ;;
    -u|--user=*)
    USER="${i#*=}"
    shift
    ;;
    -s|--secret-file=*)
    SECRET_FILE="${i#*=}"
    shift
    ;;
    -p|--sudo-password=*)
    USER_SUDO_PASS="${i#*=}"
    shift
    ;;
    *)
      help
      exit $EXIT_FAILURE
    ;;
esac
done

if [ -z $ENVIRONMENT ]; then
  help
  exit $EXIT_FAILURE
fi

cd production/infra
  terraform workspace select $ENVIRONMENT
  terraform apply -input=false
cd -

cd production/configuration
  ansible-playbook \
    -u $USER \
    -i inventory-$ENVIRONMENT \
    -e "current_environment=$ENVIRONMENT ansible_sudo_pass=$USER_SUDO_PASS" \
    --vault-password-file=$SECRET_FILE \
    deploy.yml
cd -
