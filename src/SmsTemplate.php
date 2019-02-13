<?php

namespace Wangyuanhui\SmsTemplate;

use Illuminate\Support\Facades\Schema;
use Wangyuanhui\SmsTemplate\Models\SmsTemplate as SmsTemplateModel;

/**
 * Service class for SmsTemplate
 * @package Wangyuanhui\SmsTemplate
 */
class SmsTemplate
{
    /**
     * @var string
     */
    protected $table = 'sms_templates';

    /**
     * @var string
     */
    protected $directive;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->configure();
        if (! $this->databaseTableExists()) {
            throw new \Exception("Table not found");
        }
    }

    /**
     * create a SmsTemplate
     * @param  string $title
     * @param  string $content
     * @param  string $group
     * @param  string $language
     * @return SmsTemplateModel
     */
    public function create($title, $content, $group, $language)
    {
        $template = $this->newModel();
        $template->title = $title;
        $template->content = $content;
        $template->language = $language;
        $template->group = $group;
        $template->save();
        return $template;
    }

    /**
     * return directive with key
     * @param  string $key
     * @return string directive
     */
    public function directive($key)
    {
        return str_replace('key', $key, $this->directive);
    }

    /**
     * compose message with template (int for id, string for title)
     * @param  int|string $key id/title
     * @param  array $variables [key:value]
     * @return string
     * @throws Exception template not found
     */
    public function compose($key, $variables = [])
    {
        $template = $this->get($key);
        if ($template === null) {
            throw new \Exception("Template not found");
        }
        $content = $template->content;
        if (empty($variables)) {
            return $content;
        }
        $directives = $this->directiveKeys(array_keys($variables));
        return str_replace($directives, array_values($variables), $content);
    }

    /**
     * return all templates
     * @return Collection
     */
    public function all()
    {
        $model = $this->newModel();
        return $model->select()->get();
    }

    /**
     * get by id/title/group+language (int for id, string for title, string string for group and language)
     * return null if not found
     * @param  int|string|string,string $id|$title|$group,$language
     * @return SmsTemplateModel|null
     * @throws Exception Invalid arguments
     */
    public function get(...$key)
    {
        if (count($key) == 1) {
            $key = $key[0];
            if (is_int($key)) {
                return $this->getById($key);
            }
            if (is_string($key)) {
                return $this->getByTitle($key);
            }
        }
        if (count($key == 2)) {
            if (is_string($key[0]) && is_string($key[1])) {
                $group = $key[0];
                $language = $key[1];
                return $this->getByGroupLanguage($group, $language);
            }
        }
        throw new \Exception('Invalid arguments');
    }

    /**
     * get SmsTemplate by id
     * return null if not found
     * @param  int $id
     * @return SmsTemplateModel|null
     */
    public function getById($id)
    {
        $model = $this->newModel();
        return $model->find($id);
    }

    /**
     * get SmsTemplate by title
     * return null if not found
     * @param  string $title
     * @return SmsTemplateModel|null
     */
    public function getByTitle($title)
    {
        $model = $this->newModel();
        return $model->select()->where('title', $title)->first();
    }

    /**
     * get SmsTemplate by group and language
     * return null if not found
     * @param  string $group
     * @param  string $language
     * @return SmsTemplateModel|null
     */
    public function getByGroupLanguage($group, $language)
    {
        $model = $this->newModel();
        return $model->select()->where('group', $group)->where('language', $language)->first();
    }

    /**
     * update by id
     * @param  int $id
     * @param  string $title
     * @param  string $content
     * @param  string $group
     * @param  string $language
     * @return int
     */
    public function update($id, $title = null, $content = null, $group = null, $language = null)
    {
        $attributes = [];
        if ($title) {
            $attributes['title'] = $title;
        }
        if ($content) {
            $attributes['content'] = $content;
        }
        if ($language) {
            $attributes['language'] = $language;
        }
        if ($group) {
            $attributes['group'] = $group;
        }
        if (empty($attributes)) {
            return 0;
        }
        $model = $this->newModel();
        return $model->where('id', $id)->update($attributes);
    }

    /**
     * delete by id/title/group+language (int for id, string for title, string string for group and language)
     * return null if not found
     * @param  int|string|string,string $id|$title|$group,$language
     * @return int
     * @throws Exception Invalid arguments
     */
    public function delete(...$key)
    {
        if (count($key) == 1) {
            $key = $key[0];
            if (is_int($key)) {
                return $this->deleteById($key);
            }
            if (is_string($key)) {
                return $this->deleteByTitle($key);
            }
        }
        if (count($key == 2)) {
            if (is_string($key[0]) && is_string($key[1])) {
                $group = $key[0];
                $language = $key[1];
                return $this->deleteByGroupLanguage($group, $language);
            }
        }
        throw new \Exception('Invalid arguments');
    }

    /**
     * delete SmsTempalte by id
     * @param  int $id
     * @return int
     */
    public function deleteById($id)
    {
        $model = $this->newModel();
        return $model->where('id', $id)->delete($id);
    }

    /**
     * delete SmsTempalte by title
     * @param  string $title
     * @return int
     */
    public function deleteByTitle($title)
    {
        $model = $this->newModel();
        return $model->where('title', $title)->delete();
    }

    /**
     * delete SmsTempalte by group and language
     * @param  string $group
     * @param  string $language
     * @return int
     */
    public function deleteByGroupLanguage($group, $language)
    {
        $model = $this->newModel();
        return $model->where('group', $group)->where('language', $language)->delete();
    }

    /**
     * new Model instance
     * @return SmsTemplateModel
     */
    public function newModel()
    {
        $model = new SmsTemplateModel();
        $model->setTable($this->table);
        return $model;
    }

    /**
     * check if table exists
     * @return bool
     */
    private function databaseTableExists()
    {
        return Schema::hasTable($this->table);
    }

    private function configure()
    {
        if (config()->has('smstemplate.directive')) {
            $this->directive = config('smstemplate.directive');
        }
        else {
            $this->directive = (require __DIR__.'/../config/smstemplate.php')['directive'];
        }
    }

    /**
     * return directives with keys
     * @param  string
     * @return array
     */
    private function directiveKeys($keys)
    {
        $directives = [];
        foreach ($keys as $key) {
            $directives []= $this->directive($key);
        }
        return $directives;
    }
}