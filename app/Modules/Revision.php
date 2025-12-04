<?php

namespace Otomaties\Core\Modules;

use Otomaties\Core\View;

class Revision
{
    /**
     * Release information, with Revision & Timestamp keys
     *
     * @var array<string, string>
     */
    private ?array $releaseInformation = null;

    /**
     * Add actions and filters
     */
    public function init(): void
    {
        add_filter('update_footer', [$this, 'showRevisionInAdminFooter'], 999);
        add_action('wp_footer', [$this, 'showRevisionInConsole'], 999);
    }

    /**
     * Show revision in console
     */
    public function showRevisionInConsole(): void
    {
        if (! apply_filters('otomaties_display_revision', true)) {
            return;
        }

        $releaseInformation = $this->releaseInformation();

        if (empty($releaseInformation) || otomatiesCore()->environment() === 'production') {
            return;
        }
        otomatiesCore()
            ->make(View::class)
            ->render('revision/console', [
                'releaseInformation' => $releaseInformation,
            ]);
    }

    /**
     * Show revision in admin footer
     */
    public function showRevisionInAdminFooter(?string $text = ''): string
    {
        $text = $text ?? '';
        $releaseInformation = $this->releaseInformation();

        if (empty($releaseInformation) || ! current_user_can('manage_options')) {
            return $text;
        }

        foreach ($releaseInformation as $key => $value) {
            $text .= sprintf(' | %s: <strong>%s</strong>', $key, $value);
        }

        return $text;
    }

    /**
     * Get release information and store it
     *
     * @return array<string, string>
     */
    private function releaseInformation(): array
    {
        if ($this->releaseInformation) {
            return $this->releaseInformation;
        }

        $revisionFileContent = $this->getRevisionFileContent();
        $this->releaseInformation = $revisionFileContent ? $this->parseRevisionFileContent($revisionFileContent) : [];

        return $this->releaseInformation;
    }

    /**
     * Parse revision file and store date and hash in releaseInformation
     *
     * @return array<string, string>
     */
    private function parseRevisionFileContent(string $revisionFileContent): array
    {
        $return = [];

        $revisionFileContent = str_replace(PHP_EOL, '', $revisionFileContent);
        $revisionFileContentArray = explode(' ', $revisionFileContent);
        if (count($revisionFileContentArray) >= 2) {
            $return[__('Timestamp', 'otomaties-core')] = $revisionFileContentArray[0];
            if (is_numeric($revisionFileContentArray[0]) && mb_strlen($revisionFileContentArray[0]) === 14) {
                $dateTime = \DateTimeImmutable::createFromFormat('YmdHis', $revisionFileContentArray[0]);
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
     */
    private function getRevisionFileContent(): ?string
    {
        $revisionFilePath = $this->findRevisionFilePath();
        if (! $revisionFilePath || ! file_exists($revisionFilePath)) {
            return null;
        }
        $resource = fopen($revisionFilePath, 'r');
        if (! $resource) {
            return null;
        }
        $content = fgets($resource);

        return $content ? $content : '';
    }

    /**
     * Find revision file path
     */
    private function findRevisionFilePath(): ?string
    {
        $possibleLocations = [
            ABSPATH . 'revision.txt',
            str_replace('/wp/', '/', ABSPATH . 'revision.txt'),
        ];
        foreach ($possibleLocations as $location) {
            if (file_exists($location)) {
                return $location;
            }
        }

        return null;
    }
}
