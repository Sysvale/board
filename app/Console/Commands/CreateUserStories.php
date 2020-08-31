<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Console\Command;

class CreateUserStories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:stories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[TEMPORÁRIO] Comando para preencher a base com histórias já escritas e priorizadas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user_stories = collect([
            [
                'title' => 'Para realizar um agendamento, eu, como recepcionista da UBS, gostaria de consumir cotas de um procedimento.',
                'acceptance_criteria' => [
                    'Diretor da regulação deverá separar as vagas para as unidades',
                    'O recepcionista deverá consumir as vagas que foram separadas',
                    'O Diretor/operador poderá consumir vagas, mesmo que ela esteja alocada (mas não consumida) para uma unidade',
                    'Usuários afetados: Diretor, operador da regulação e recepcionista'
                ]
            ],
            [
                'title' => 'Para melhor acompanhamento das notificações de COVID, eu como administrador gostaria de visualizar as notificações agrupadas por bairro.',
                'acceptance_criteria' => [
                    'O admin deve conseguir ver a qtd de notificações por bairro',
                    'Usuários afetados: admin'
                ]
            ],
            [
                'title' => 'Para melhor gerenciamento do inventário, eu, como usuário, abro um ticket para defeito em equipamento ou roubo.',
                'acceptance_criteria' => [
                    'O usuário deve conseguir abrir um ticket específico pra roubo OU defeito',
                    'Usuários afetados: todos que abrem ticket',
                ]
            ],
            [
                'title' => 'Para que consiga acessar as novas cidades, eu, como usuário padrão gostaria de ter minha conta criada automaticamente quando o time liberar o servidor.',
                'acceptance_criteria' => [
                    'Para cláudio, devem ser criadas a conta de admin e suporte',
                    'Para denissao: admin e sudo',
                    'Para naíra: suporte e admin'
                ]
            ],
            [
                'title' => 'Para um melhor acompanhamento das demandas, eu, como dev, receberei notificação de criação/interação em tickets.',
                'acceptance_criteria' => [
                    'Todas as mensagens do ticket devem ser enviadas pro rocket chat',
                    'Usuários afetados: todos que interagem com tickets'
                ]
            ],
            [
                'title' => 'Para conhecer melhor os produtos do Cidade Saudável, eu como visitante, verei a nova landing page.',
                'acceptance_criteria' => [
                    'Quem acessar a LP deverá visualizar as páginas inicial e de detalhamento dos produtos de acordo com o mockup',
                ]
            ],
            [
                'title' => 'Para dar a carga inicial de usuários, eu, como suporte/representante importarei o XML do município.',
                'acceptance_criteria' => [
                    'O XML só pode ser importado para cidades em processo de implantação e que não tenha sido importado ainda',
                    'Usuários afetados: suporte/representante'
                ]
            ],
            [
                'title' => 'Para que eu possa iniciar o treinamento, eu, como suporte/representante, importarei a planilha com o mapeamento das ruas para os ACS.',
                'acceptance_criteria' => [
                    'A importação só pode ser feita para cidades em processo de implantação e que não tenha sido feito o mapeamento ainda',
                    'Deve haver uma interface pra realizar a importação',
                    'Usuários afetados: suporte/representante'
                ]
            ],
            [
                'title' => 'Para um melhor acompanhamento dos bloqueios, eu, como financeiro, gostaria de armazenar as informações de bloqueio/desbloqueio de uma cidade.',
                'acceptance_criteria' => [
                    'Devem ser salvos o nível de bloqueio/desbloqueio, data de alteração e quem fez a alteração',
                    'Usuários afetados: financeiro'
                ]
            ],
            [
                'title' => 'Para um melhor gerenciamento das notas fiscais, eu, como financeiro, insiro valores de licitação de uma cidade.',
                'acceptance_criteria' => [
                    'Deve ser possível inserir: valor implantação, valor licença mensal ACS, valor licença mensal ACE, valor unitário ACS, Valor unitário ACE e valor Global',
                    'Usuários afetados: financeiro'
                ]
            ],
            [
                'title' => 'Para um melhor acompanhamento das demandas, eu, como usuário, recebo uma notificação quando houver uma nova interação no ticket.',
                'acceptance_criteria' => [
                    'Em qualquer parte da aplicação, deve ser possível ver as notificações dos tickets',
                    'Usuários afetados: todos aqueles que interagem com tickets'
                ]
            ],
        ]);

        $backlog = BoardList::where('key', BoardListsKeys::BACKLOG)->first();

        $user_stories->each(function ($item, $position) use ($backlog) {
            $card = Card::create([
                'title' => $item['title'],
                'acceptance_criteria' => $item['acceptance_criteria'],
                'position' => $position,
                'board_list_id' => $backlog->id,
                'is_user_story' => true
            ]);

            $this->info('Historia ' .$position . ' criada - ' . $card->title);
        });
    }
}
