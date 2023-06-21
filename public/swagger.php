openapi: 3.0.0
info:
  title: Api-pastelaria
  description: 'Api para gerenciar pedidos do dia em uma pastelaria'
  version: '0.1'
servers:
  -
    url: 'https://localhost:8989/api/'
    description: env
paths:
  /api/login:
    post:
      operationId: c238c0839a4aa51be68b89a141225649
      responses:
        '200':
          description: 'Faz autenticação de um usuário já cadastrado e retorna o token de acesso para api'
