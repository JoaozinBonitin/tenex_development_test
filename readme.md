## Agradecimentos

Meus mais sinceros agradecimentos à empresa Tenex pela oportunidade de participar deste teste de desenvolvimento. A experiência foi enriquecedora e me permitiu demonstrar habilidades no desenvolvimento de APIs e no uso de tecnologias mais robustas.

# Projeto de API RESTful - Gestão de Carnês

Este projeto implementa uma API RESTful em PHP para gerenciar carnês de pagamento, incluindo a criação e recuperação de parcelas. A API permite a divisão do valor total em parcelas, considerando a possibilidade de uma entrada. 

# Funcionalidades

1. **Criação de um Carnê**:
   - Permite dividir um valor total em parcelas.
   - Considera a possibilidade de uma entrada, que é tratada como uma parcela independente.
   - Permite a configuração da periodicidade das parcelas (mensal ou semanal).

2. **Recuperação de Parcelas**:
   - Permite recuperar as parcelas associadas a um carnê existente.

## Tecnologias Utilizadas

- **Symfony**: Framework PHP utilizado para desenvolver a API, garantindo uma estrutura robusta e escalável.
- **PostgreSQL**: Banco de dados utilizado para armazenar informações dos carnês e parcelas.
- **Docker**: Contêinerização do ambiente de desenvolvimento, proporcionando consistência e facilidade na configuração.

## Instalação e Configuração

### Pré-requisitos

- PHP 8.0 ou superior
- Composer (Link de Instalação: https://getcomposer.org/download/)
- Symfony CLI (Link de instalação: https://symfony.com/download)
- Docker (Link de Instalação: https://docs.docker.com/desktop/install/windows-install)

## Instalação

1. **Clone o repositório:**

   ```bash
   git clone <URL_DO_REPOSITORIO>

2. **Rode a Imagem do Projeto**

   ```bash
   docker compose up -d 

3. **Execute as migrations**

   ```bash
   symfony console make:migration
   symfony console doctrine:migrations:migrate  

## Uso

1. **Rode o Servidor Symfony:**
    ```bash
    symfony serve 

2. **Criando um Carnê:**
    
    Envie uma requisição POST para /carnet com o corpo no formato JSON:

    ![Exemplo de criação de Carnê](/public/assets/img/create-carnet-example.png)
    
3. **Recuperação de Parcelas**
    
    Envie uma requisição GET para /carnet/{id}/parcelas com o corpo no formato JSON:

    ![Exemplo de criação de Carnê](/public/assets/img/consult-carnet-example.png)    
