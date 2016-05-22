<?php
namespace TestHelper;
use Service\ConfigParams;


class ConfigParamsBuilder
{
    /**
     * @return ConfigParams
     */
    public static function createConfigParams()
    {
        return new ConfigParams(
            APP_PATH,
            APP_URL_BASE,
            APP_INDEX,
            APP_TEMPLATE_PATH,
            APP_TITLE,
            APP_VERSION,
            APP_DATABASE_FILE
        );
    }
}
