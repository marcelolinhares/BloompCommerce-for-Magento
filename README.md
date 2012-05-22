#Plugin do Bloomp Commerce para Magento
Módulo desenvolvido pela equipe Bloompa.  
Versão atual: 1.0

##Sobre:

###O que é Bloompa?
O Bloompa é uma startup que nasceu através de pesquisas sobre o mercado de social commerce. Bloompa é a camada social dentro dos e-commerces!  
Acesse nosso site(<http://www.bloompa.com.br>) e saiba mais sobre nossas soluções.

###O que é Bloomp Commerce?
É um conjunto de soluções que aumentam a taxa de conversão de vendas das lojas virtuais usando o poder das redes sociais, com um novo jeito de engajar, analisar e capturar mais e melhores clientes.


***
**ATENÇÃO:** Antes de instalar o plugin você deve cadastrar sua loja em <http://www.bloompa.com.br>.
***


## Sobre o módulo
Este módulo foi forkeado do módulo enviado pelo time da própria Bloompa, porém ele precisou de algumas mudanças para funcionar, e ainda precisa de MUITAS mudanças  (ver TODO abaixo)  para ficar adequado ao padrão da plataforma Magento.


## Compatibilidade
Está funcionando de forma estável em: [maisfloresbh.com.br](http://www.maisfloresbh.com.br), que utiliza a versão 1.6, não testei em outras versões.

## Instalando o módulo
***
**Em passos:**
***

**Passo 1** - Copiar a pasta "app" para dentro do seu projeto

**Passo 2** - Executar "qualquer" página do Magento, validar se a tabela "bloompa" foi criada no BD

**Passo 3** - Persistir o seu token do Bloompa na tabela token: SQL (INSERT INTO `flores_b2c`.`bloompa` (`id_token`, `token`) VALUES ('2', 'SEU_TOKEN_AQUI');

**Passo 4** - Validar na tela do carrinho, se apareceu os botões de compartilhamento no facebook e no twitter

**Passo 5** - Verificar se o código do CUPOM foi criado no Magento (Promoções >  Promoção de Carrinho)

## Configurando

## Mudando o bloco de lugar

Se quiser alterar a localização dos botões de compartilhamento, editar via estilo a div com id "bloompa-cart-widget"

Caso deseje, pode colocar o bloco no **cart.phtml** do seu template, ao invés de deixar no **bloompa_cart.phtml**

`
<divv id="bloompa-cart-widget" style="float: left; margin-right: 40px;" ></divv>
`

Outra opção é alterar o posicionamento do bloco em:
`
        <reference name="content">
            <block type="page/html" name="bloompa" after="-" template="bloompa/bloompa_cart.phtml"/>
        </reference>      
`
  
        Onde o campo reference pode ser: content/footer/header/right/left

## Features não homologados

## TODO
- Tirar SQL das views
- Retirar a tabela bloompa (ela pode ficar persistido na core_config_data)
- personalizar o label do cupom dinamicamente
- limpar o código que não está funcionando 
- homologar a visualização do widget na página do produto