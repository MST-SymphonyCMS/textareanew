# Textarea (New)

Improved textarea field for [Symphony][1].

**Proof of concept.**


## Output

### Unformatted values

Output for unformatted values should be streamlined and made consistent across fields, see [discussion][2] for reference.

Unformatted values should always be treated as text (not code) and wrapped in CDATA.

No need for fields to use `__replaceAmpersands` and `General::sanitize`.

Nasty characters are encoded by default by the templating layer.

Data source `HTML-encode text` option is redundant and should be ignored for unformatted values.


### Formatted values

Formatted values should always be treated as code.

Text formatters are expected to provide valid XML.

Data source `HTML-encode text` option wraps fromatted values in CDATA for use as text instead of code.


## Text formatters

Multiple, chained text formatters can be added now.

Formatters can be added, changed or removed without resaving entries.


### Conceptual change

Text formatters are a presentational thing and should ideally be part of the templating layer.


### Implementation details

Formatters as XSLT utilities would be too inconvenient, so I guess running formatters during XML creation would be a good compromise.

In consequence, formatted values shouldn't be stored as field content.

Fields using text formatters should use caching instead.

(Caching isn't implemented yet).


#### Changes to the core

When trying to disable or uninstall an extension with a text formatter that's currently used by a field, Symphony refuses to do so with a warning.

Changes to the core are necessary to keep this intact.

[github.com/jensscherbl/symphony-2/commit/823d0e4d5423f8589885ebefe12788d424f19f0f][9]


## Additional thoughts

### UI

UIs (like WYSIWYG and Markdown editors) should be added to textareas separately from text formatters.

Similar to what Nils did with [Association Field][3].


### Text Box Field

Input field and textarea field should remain two separate fields.

Input field has validation and doesn't need formatters, textarea has formatters and doesn't need validation.

Maybe I'm just missing some good arguments at the moment, opinion on this might change.

See [github.com/psychoticmeow/textboxfield][6] for reference.


### Modifiers

In addition to text formatters, another thing called *modifiers* could be added to both, input field and textarea field.

Unlike a text formatter, a modifier actually changes the value stored in a field, and isn't expected (or allowed) to provide XML.

Modifiers could be used for [text replacement][7], [whitespace trimming][8], etc.

In addition to being provided by extensions, custom modifiers could be stored in the workspace folder, similar to [JIT Image][4] recipes.

Maybe custom modifiers could even be used to provide functionality similar to [Reflection Field][5] in the future.


[1]: http://getsymphony.com
[2]: https://github.com/symphonycms/symphony-2/issues/2292
[3]: https://github.com/symphonists/association_field
[4]: https://github.com/symphonycms/jit_image_manipulation
[5]: https://github.com/symphonists/reflectionfield
[6]: https://github.com/psychoticmeow/textboxfield
[7]: http://daringfireball.net/projects/smartypants/
[8]: https://github.com/symphonycms/symphony-2/issues/2024
[9]: https://github.com/jensscherbl/symphony-2/commit/823d0e4d5423f8589885ebefe12788d424f19f0f