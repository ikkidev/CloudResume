# Makefile

WP_CLI_VERSION := 2.12.0
WP_CLI_URL := https://github.com/wp-cli/wp-cli/releases/download/v$(WP_CLI_VERSION)/wp-cli-$(WP_CLI_VERSION).phar

install-wp-cli:
	@echo "Installing wp-cli version $(WP_CLI_VERSION)..."
	if [ ! -f /usr/local/bin/wp ]; then \
		echo "Downloading wp-cli from $(WP_CLI_URL)..."; \
		sudo curl --silent --fail --location --remote-name $(WP_CLI_URL); \
		if [ $$? -ne 0 ]; then \
			echo "Failed to download wp-cli"; \
			exit 1; \
		fi; \
		sudo chmod +x wp-cli-$(WP_CLI_VERSION).phar; \
		sudo mv wp-cli-$(WP_CLI_VERSION).phar /usr/local/bin/wp; \
	else \
		echo "wp-cli is already installed."; \
	fi