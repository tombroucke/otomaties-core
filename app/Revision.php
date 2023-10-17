<?php
namespace Otomaties\Core;

class Revision
{
    /**
     * Release information, with Revision & Timestamp keys
     *
     * @var array<string, string>
     */
    private array $releaseInformation = [];

    public function __construct(private string $wpEnv = 'production')
    {
        $revisionFileContent = $this->getRevisionFileContent();
        if ($revisionFileContent) {
            $this->releaseInformation = $this->parseRevisionFileContent($revisionFileContent);
        }
    }

    /**
     * Parse revision file and store date and hash in releaseInformation
     *
     * @return array<string, string>
     */
    private function parseRevisionFileContent(string $revisionFileContent) : array
    {
        $return = [];
        
        $revisionFileContent = str_replace(PHP_EOL, '', $revisionFileContent);
        $revisionFileContentArray = explode(' ', $revisionFileContent);
        if (count($revisionFileContentArray) >= 2) {
            $return[__('Timestamp', 'otomaties-core')] = $revisionFileContentArray[0];
            if (is_numeric($revisionFileContentArray[0]) && strlen($revisionFileContentArray[0]) == 14) {
                $dateTime = \DateTime::createFromFormat('YmdHis', $revisionFileContentArray[0]);
                if ($dateTime) {
                    $return[__('Timestamp', 'otomaties-core')] = $dateTime->format('d-m-y H:i:s');
                }
            }
            $return[__('Revision', 'otomaties-core')] = $revisionFileContentArray[1];
        } else {
             $return[__('Revision', 'otomaties-core')] = $revisionFileContent;
        }
        return $return;
    }

    /**
     * Get revision file content
     *
     * @return string|null
     */
    private function getRevisionFileContent() : ?string
    {
        $revisionFilePath = $this->findRevisionFilePath();
        if (!$revisionFilePath || !file_get_contents($revisionFilePath)) {
            return null;
        }
        $resource = fopen($revisionFilePath, 'r');
        if (!$resource) {
            return null;
        }
        $content = fgets($resource);
        return $content ? $content : '';
    }

    /**
     * Find revision file path
     *
     * @return string|null
     */
    private function findRevisionFilePath() : ?string
    {
        $possibleLocations = [
            ABSPATH . 'revision.txt',
            str_replace('/wp/', '/', ABSPATH . 'revision.txt')
        ];
        foreach ($possibleLocations as $location) {
            if (file_exists($location)) {
                return $location;
            }
        }
        return null;
    }

    /**
     * Show revision in console
     *
     * @return void
     */
    public function showRevisionInConsole() : void
    {
        if (empty($this->releaseInformation) || 'production' == $this->wpEnv) {
            return;
        }
        ?>
        <script>
            let revisionData = <?php echo json_encode($this->releaseInformation); ?>;
            for(const key in revisionData) {
                console.log(`${key}: ${revisionData[key]}`);
            }
        </script>
        <?php
    }

    /**
     * Show revision in admin footer
     *
     * @param string $text
     * @return string
     */
    public function showRevisionInAdminFooter(?string $text = '') : string
    {
        $text = $text ?? '';

        if (empty($this->releaseInformation) || !current_user_can('manage_options')) {
            return $text;
        }

        foreach ($this->releaseInformation as $key => $value) {
            $text .= sprintf(' | %s: <strong>%s</strong>', $key, $value);
        }
        
        return $text;
    }
}
