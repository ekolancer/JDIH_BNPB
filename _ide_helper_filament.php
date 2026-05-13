<?php

/**
 * Filament v4 IDE Helper Stubs
 * This file provides type hints for Intelephense
 */

namespace Filament\Schemas {

    class Schema
    {
        /**
         * @param array $components
         * @return static
         */
        public function schema(array $components): static
        {
            return $this;
        }

        /**
         * @param array $components
         * @return static
         */
        public function components(array $components): static
        {
            return $this;
        }
    }
}

namespace Filament\Schemas\Components {

    class Wizard
    {
        /**
         * @param array $steps
         * @return static
         */
        public static function make(array $steps = []): static
        {
            return new static();
        }

        /**
         * @return static
         */
        public function submitAction($action): static
        {
            return $this;
        }

        /**
         * @return static
         */
        public function columnSpan($span): static
        {
            return $this;
        }
    }

    class Fieldset
    {
        /**
         * @return static
         */
        public static function make(string $label = ''): static
        {
            return new static();
        }

        /**
         * @param array $components
         * @return static
         */
        public function schema(array $components): static
        {
            return $this;
        }
    }
}

namespace Filament\Schemas\Components\Wizard {

    class Step
    {
        /**
         * @return static
         */
        public static function make(string $label): static
        {
            return new static();
        }

        /**
         * @return static
         */
        public function completedIcon(string $icon): static
        {
            return $this;
        }

        /**
         * @param array $components
         * @return static
         */
        public function schema(array $components): static
        {
            return $this;
        }
    }
}

namespace Filament\Resources\Pages {

    class Page
    {
        /**
         * @param string $path
         * @return array
         */
        public static function route(string $path): array
        {
            return [];
        }
    }

    class ListRecords extends Page {}
    class CreateRecord extends Page {}
    class EditRecord extends Page {}
    class ViewRecord extends Page {}
}
