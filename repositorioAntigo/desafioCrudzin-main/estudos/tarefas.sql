CREATE TABLE tarefas (
    id INTEGER NOT NULL AUTO_INCREMENT,
    nome_tarefa VARCHAR(50) NOT NULL,
    categorias_permitidas VARCHAR(1) NOT NULL, /* V=Vestibular, P=Pessoal, E=Escola */
    duracao_minutos INTEGER NOT NULL,
    prioridade VARCHAR(1) NOT NULL, /* */
    data_entrega DATE NOT NULL,
    CONSTRAINT pk_tarefas PRIMARY KEY (id)
);


eu pretendo salvar a palavra inteira no banco (ex: "Programação")