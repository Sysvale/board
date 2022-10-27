<!-- omit in toc -->
# Guia de contribuição do Trelássio

Primeiramente, obrigado pelo tempo e iniciativa em contribuir com o Trelássio! ❤️


Todas as contribuições são encorajadas e bem-vindas. Consulte o [Índice](#Índice) para obter diferentes maneiras de ajudar. Por favor, assegure-se de ler as seções do índice relacionadas à sua contribuição antes de abrir algum PR ou issue, isso vai tornar o processo de avaliação e discussão mais ágil e garantir que todos estarão na mesma página. 🎉

> E se você gosta do projeto, mas não tem tempo para contribuir, tudo bem. Existem outras maneiras fáceis de apoiar o projeto e mostrar o seu apreço, com as quais também ficaríamos muito felizes:
> - Dê uma estrela no repositório
> - Comente nas redes sociais sobre o Trelássio
> - Mencione o projeto em encontros locais e conte a seus amigos/colegas

<!-- omit in toc -->
## Índice

1. [Tenho uma dúvida](#tenho-uma-dúvida)
1. [Como contribuir?](#como-contribuir)
   1. [Aviso legal](#aviso-legal)
   1. [Reportando um bug](#reportando-um-bug)
   1. [Como faço um bug report](#como-faço-um-bug-report)
   1. [Solicitando uma feature](#solicitando-uma-feature)
1. [Git Strategy](#git-strategy)

## Tenho uma dúvida

Antes de fazer uma pergunta, é melhor pesquisar por [Issues](https://github.com/Sysvale/board/issues) que podem te ajudar. Caso você tenha encontrado uma issue similar e ainda assim precise de esclarecimentos, você pode escrever a sua pergunta como comentário dessa issue.

Se você ainda sentir a necessidade de fazer uma pergunta e precisar de esclarecimento, recomendamos o seguinte:

- Abra uma [Issue](https://github.com/Sysvale/board/issues/new).
- Forneça o máximo de contexto possível sobre o que está acontecendo.

Em seguida, cuidaremos do problema o mais rápido possível.

## Como contribuir?

### Aviso Legal
>  Ao contribuir para este projeto você declara que é autor de 100% do conteúdo, que possui os direitos necessários sobre o conteúdo e que a melhoria com a qual contribui pode ser fornecida sob a licença do projeto.

Para configurar o projeto localmente basta seguir os passos do [README](https://github.com/Sysvale/board/blob/develop/README.md).

### Reportando um bug

<!-- omit in toc -->
#### Antes de submeter um bug report

Antes de abrir uma issue reportando um bug é importante assegurar-se de que o erro que está acontecendo com você pode ser reproduzido por outras pessoas e que você tem em mãos informações suficientes para que elas possam te ajudar. Para tanto, pedimos que cheque o seguinte:

-   Certifique-se de estar usando a última versão do Trelássio;
-   Verifique se o problema que está acontecendo não é um erro do seu ambiente de desenvolvimento;
-   Procure na [lista de issues](https://github.com/Sysvale/board/issues) se já não há um bug report semelhante ou igual ao seu;

#### Como faço um bug report?

Nesse momento precisamos que você informe:

-   Resumo do problema (prints e gifs podem ser muito úteis):
    -   Passos para reproduzir o problema
    -   Comportamento atual
    -   Comportamento esperado
-   Versão do Trelássio;
-   Versão das principais dependências relacionadas ao problema;
-   Navegador utilizado;
-   Versão do navegador;
-   SO utilizado;
-   Versão do SO;

### Solicitando uma feature

Esta seção orienta você no envio de uma sugestão de aprimoramento para o Trelássio, **incluindo recursos completamente novos e pequenas melhorias nas funcionalidades existentes**. Seguir essas diretrizes ajudará os mantenedores e a comunidade a entender sua sugestão e encontrar sugestões relacionadas.

<!-- omit in toc -->
#### Antes de submeter uma melhoria

- Cheque na [lista de issues](https://github.com/Sysvale/board/issues) para ver se o aprimoramento já foi sugerido. Se houver, adicione um comentário à sugestão existente em vez de abrir uma nova.
- Descubra se sua ideia se encaixa com o escopo e os objetivos do projeto. Cabe a você fazer um argumento forte para convencer os desenvolvedores do projeto dos méritos desse recurso.

## Git strategy

### Branches

#### Branch base

Nosso branch base é o `master` e ele sempre representa o último código enviado para produção. Qualquer novo branch deve ser criado a partir dele e merjado para ele, a menos que haja uma versão específica em planejamento, contendo várias contribuições. Nesse cenário deve-se criar um branch de versão (Ex.: `release/v3.0.0`) a partir do `master` e as features dessa versão devem ser direcionadas ao branch de versão.

#### Branches temporários

Utilizamos três tipos de branch temporários. Eles ajudam a categorizar o tipo de modificação que está sendo submetida ao branch base e seguem a seguinte convenção de nomenclatura:

-   **Feature**: `feature/nome-da-feature`
-   **Bug fix**: `fix/nome-da-correção`
-   **Build**: `build/nome-da-modificação-no-build`

Os branches temporários, têm nomes em kebab-case, têm um tempo limite de existência e, uma vez mesclados ou fechados, devem ser excluídos.

#### Exemplo de fluxo de trabalho

-   Atualize sua versão local do `master`;
-   A partir do master, crie um branch seguindo as convenções de nomenclatura mencionadas acima;
-   Crie um pull request e marque-o como _work in progress_ (WIP) até ter finalizado o desenvolvimento;
-   Uma vez pronto para review, solicite que algum dev avalie seu código. Dois approvals são necessários, mas approvals nunca são de mais, sobretudo em implementações mais complexas 😉;
-   Uma vez aprovado o PR, o seu branch será mesclado no `develop`;
-   Delete seu branch;
-   Crie uma nota de release;

<br>
<br>
