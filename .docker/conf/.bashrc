export TZ=America/Recife

echo "   _  *** ${BASE_IMAGE} ***"
echo "  *v* *** ${IMAGE_ALIAS} ***"
echo " /(_)\ "
echo "  ^ ^  " `date`

echo -e ${INFO_IMAGE}

export PS1="\[$(tput bold)\][\[\e[33m\]${BASE_IMAGE}${SEPARATOR}${IMAGE_ALIAS}\[\e[m\]\[$(tput bold)\]] \[$(tput bold)\]\[\033[38;5;4m\]\w\[$(tput sgr0)\]\[$(tput sgr0)\]\[\033[38;5;15m\]\[$(tput sgr0)\]\$(__git_ps1) # "

alias ls='ls --color=auto'

if ! shopt -oq posix; then
  if [ -f /usr/share/bash-completion/bash_completion ]; then
    . /usr/share/bash-completion/bash_completion
  elif [ -f /etc/bash_completion ]; then
    . /etc/bash_completion
  fi
fi
