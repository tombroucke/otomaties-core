<?php
namespace Otomaties\Core;

class Revision
{
    private $release = [];
    private $revisionFileContent = '';
    private $wpEnv = 'production';

    public function __construct(string $wpEnv)
    {
        $this->wpEnv = $wpEnv;
        $revisionFile = $this->findRevisionFile();
        if ($revisionFile && file_get_contents($revisionFile)) {
            $this->revisionFileContent = str_replace(PHP_EOL, '', fgets(fopen($revisionFile, 'r')));
            $revisionFileContentArray = explode(' ', $this->revisionFileContent);
            if (count($revisionFileContentArray) >= 2) {
                $this->release[__('Timestamp', 'otomaties-core')] = $revisionFileContentArray[0];
                if (is_numeric($revisionFileContentArray[0]) && strlen($revisionFileContentArray[0]) == 14) {
                    $dateTime = \DateTime::createFromFormat('YmdHis', $revisionFileContentArray[0]);
                    $this->release[__('Timestamp', 'otomaties-core')] = $dateTime->format('d-m-y H:i:s');
                }

                $this->release[__('Revision', 'otomaties-core')] = $revisionFileContentArray[1];
            } else {
                $this->release[__('Revision', 'otomaties-core')] = $this->revisionFileContent;
            }
        }
    }

    private function findRevisionFile()
    {
        $filePath = ABSPATH . 'revision.txt';
        if (file_exists($filePath)) {
            return $filePath;
        }
        $filePath = str_replace('/wp/', '/', $filePath);
        if (file_exists($filePath)) {
            return $filePath;
        }
        return false;
    }

    public function showRevisionInConsole()
    {
        if (empty($this->release) || 'production' == $this->wpEnv) {
            return;
        }
        ?>
        <script>
            let revisionData = <?php echo json_encode($this->release); ?>;
            for(const key in revisionData) {
                console.log(`${key}: ${revisionData[key]}`);
            }
        </script>
        <?php
    }

    public function showRevisionInAdminFooter($text)
    {
        if (empty($this->release) || !current_user_can('manage_options')) {
            return $text;
        }
        foreach ($this->release as $key => $value) {
            $text .= sprintf(' | %s: <strong>%s</strong>', $key, $value);
        }
        return $text;
    }
}
