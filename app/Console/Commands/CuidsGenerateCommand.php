<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class CuidsGenerateCommand extends Command
{
    protected $signature = 'cuids:generate {entity} {--with-backend}';
    protected $description = 'Gera um novo módulo de CUIDS';

    protected $files;

	protected $entity;
	protected $entities;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $this->entity = Str::lower($this->argument('entity'));
		$this->entities = Str::plural($this->entity);
        $this->generateIndexPage();
		$this->generateComponents();
		$this->generateDomain();
		$this->generateRoutes();
		if($this->option('with-backend')) {
			$this->generateBackend();
		}
    }

	private function generateIndexPage() {
		// Caminho para a pasta de stubs
        $stub_path = base_path("stubs/pages/EntitiesPage.vue");

        // Verificar se o arquivo de stub existe
		if (!$this->verifyIfFileExists($stub_path)) return;

        // Fazer as substituições dinâmicas
        $file_content = $this->replaceStubs($stub_path);

        // Caminho onde o arquivo gerado será salvo
        $output_path = base_path("resources/js-vue-3/features/{$this->entities}/pages/{$this->pascalEntities()}Page.vue");

        // Criar a pasta se não existir
		$this->createIfNotExists($output_path);

        // Salvar o conteúdo gerado no novo arquivo
        $this->files->put($output_path, $file_content);

        $this->info("File created at {$output_path}");
	}

	private function generateComponents() {
		// Caminho para a pasta de stubs
		$components = [
			'CreateEntityModal' => 'singular',
			'EntitiesTable' => 'plural',
			'EntityForm' => 'singular',
			'UpdateEntityModal' => 'singular',
		];

		foreach($components as $key => $value) {
			$stub_path = base_path("stubs/components/{$key}.vue");
	
			// Verificar se o arquivo de stub existe
			if (!$this->verifyIfFileExists($stub_path)) return;
	
			// Fazer as substituições dinâmicas
			$file_content = $this->replaceStubs($stub_path);

			if($value == 'plural') {
				$component_name = str_replace('Entities',  $this->pascalEntities(), $key);
			} else {
				$component_name = str_replace('Entity', $this->pascalEntity(), $key);
			}


			$output_path = base_path("resources/js-vue-3/features/{$this->entities}/components/{$component_name}.vue");
	
			// Criar a pasta se não existir
			$this->createIfNotExists($output_path);
	
			// Salvar o conteúdo gerado no novo arquivo
			$this->files->put($output_path, $file_content);
	
			$this->info("File created at {$output_path}");
		}
	}

	private function generateDomain() {
		
		// Caminho para a pasta de stubs
		$files = [
			'model.ts',
			'index.ts',
			'pageSettings.ts',
			'service.ts',
		];

		foreach($files as $file) {
			$stub_path = base_path("stubs/domain/{$file}");
	
			// Verificar se o arquivo de stub existe
			if (!$this->verifyIfFileExists($stub_path)) return;
	
			// Fazer as substituições dinâmicas
			$file_content = $this->replaceStubs($stub_path);

			$output_path = base_path("resources/js-vue-3/shared/domain/{$this->entity}/{$file}");
	
			// Criar a pasta se não existir
			$this->createIfNotExists($output_path);
	
			// Salvar o conteúdo gerado no novo arquivo
			$this->files->put($output_path, $file_content);
	
			$this->info("File created at {$output_path}");
		}
	}

	private function generateRoutes() {
		$files = [
			'index.js',
		];

		foreach($files as $file) {
			$stub_path = base_path("stubs/routes/{$file}");
	
			// Verificar se o arquivo de stub existe
			if (!$this->verifyIfFileExists($stub_path)) return;
	
			// Fazer as substituições dinâmicas
			$file_content = $this->replaceStubs($stub_path);

			$output_path = base_path("resources/js-vue-3/features/{$this->entities}/routes/{$file}");
	
			// Criar a pasta se não existir
			$this->createIfNotExists($output_path);
	
			// Salvar o conteúdo gerado no novo arquivo
			$this->files->put($output_path, $file_content);
	
			$this->info("File created at {$output_path}");
		}
	}

	public function generateBackend() {
		Artisan::call("make:model {$this->pascalEntity()} --api --factory --requests --test");
	}

	private function pascalEntity() {
		return $this->pascalCase($this->entity);
	}

	private function pascalEntities() {
		return $this->pascalCase($this->entities);
	}

	private function snakeEntities() {
		return ucwords(strtolower($this->entities));
	}

	private function snakeEntity() {
		return ucwords(strtolower($this->entities));
	}

	private function pascalCase($string) {
		return ucwords(strtolower($string));
	}

	private function replaceStubs($stub_path) {
		// Ler o conteúdo do arquivo de stub
        $stub_content = $this->files->get($stub_path);

        // Fazer as substituições dinâmicas
        $file_content = str_replace('entity', $this->entity, $stub_content);
        $file_content = str_replace('Entity', $this->pascalEntity(), $file_content);
        $file_content = str_replace('entities', $this->entities, $file_content);
        $file_content = str_replace('Entities', $this->pascalEntities(), $file_content);
        $file_content = str_replace('ENTITY', Str::upper($this->entity), $file_content);
        $file_content = str_replace('ENTITIES', Str::upper($this->entities), $file_content);

		return $file_content;
	}

	private function verifyIfFileExists($stub_path) {
		if (!$this->files->exists($stub_path)) {
			$this->error("Stub file does not exist at path {$stub_path}");
			return false;
		}
		return true;
	}

	private function createIfNotExists($output_path) {
		if (!$this->files->exists(dirname($output_path))) {
			$this->files->makeDirectory(dirname($output_path), 0755, true);
		}
	}
} 
