# lumen4sae
lumen for sina app engine


## Getting Started

add composer:

	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/eslizn/lumen4sae"
        }
    ]

and require:
	
	"lumen4sae": "dev-master"
	
run command:

	composer update
	
edit bootstrap/app.php 

	$app->register(SaeService\Provider::class);
	
