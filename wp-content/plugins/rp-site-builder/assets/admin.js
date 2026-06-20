(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') {
      fn();
      return;
    }
    document.addEventListener('DOMContentLoaded', fn);
  }

  function field(section, key, label, type) {
    var value = section[key] || '';
    var id = 'rpsb-' + Math.random().toString(36).slice(2);
    var textarea = type === 'textarea';
    return '<label for="' + id + '">' + label + '</label>' +
      (textarea
        ? '<textarea id="' + id + '" data-rpsb-field="' + key + '" rows="4">' + escapeHtml(value) + '</textarea>'
        : '<input id="' + id + '" data-rpsb-field="' + key + '" type="' + (type || 'text') + '" value="' + escapeAttr(value) + '">');
  }

  function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, function (char) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[char];
    });
  }

  function escapeAttr(value) {
    return escapeHtml(value).replace(/"/g, '&quot;');
  }

  function renderSection(section, index) {
    var title = section.title || section.type;
    var html = '<article class="rpsb-section-editor" data-rpsb-index="' + index + '">' +
      '<header><strong>' + escapeHtml(title) + '</strong><span>' + escapeHtml(section.type) + '</span>' +
      '<button type="button" class="button-link-delete" data-rpsb-remove>Remove</button></header>' +
      '<div class="rpsb-fields">';

    if (section.type === 'hero') {
      html += field(section, 'eyebrow', 'Eyebrow') + field(section, 'title', 'Headline') + field(section, 'text', 'Intro text', 'textarea') +
        field(section, 'button_label', 'Button label') + field(section, 'button_url', 'Button URL', 'url') + field(section, 'image_url', 'Image URL', 'url') +
        field(section, 'image_alt', 'Image alt') + field(section, 'align', 'Alignment') + field(section, 'theme', 'Theme') + field(section, 'padding', 'Padding');
    } else if (section.type === 'text') {
      html += field(section, 'title', 'Heading') + field(section, 'text', 'Body copy', 'textarea') +
        '<label>Background</label><select data-rpsb-field="background"><option value="white">White</option><option value="soft">Soft</option><option value="navy">Navy</option><option value="green">Green</option></select>' +
        field(section, 'align', 'Alignment') + field(section, 'width', 'Width') + field(section, 'padding', 'Padding');
    } else if (section.type === 'image_text') {
      html += field(section, 'kicker', 'Kicker') + field(section, 'title', 'Heading') + field(section, 'text', 'Body copy', 'textarea') +
        field(section, 'button_label', 'Button label') + field(section, 'button_url', 'Button URL', 'url') + field(section, 'image_url', 'Image URL', 'url') +
        field(section, 'image_alt', 'Image alt') + field(section, 'background', 'Background') + field(section, 'align', 'Alignment') + field(section, 'padding', 'Padding');
    } else if (section.type === 'image') {
      html += field(section, 'title', 'Label') + field(section, 'image_url', 'Image URL', 'url') + field(section, 'image_alt', 'Image alt') +
        field(section, 'caption', 'Caption') + field(section, 'background', 'Background') + field(section, 'align', 'Alignment') + field(section, 'width', 'Width') + field(section, 'padding', 'Padding');
    } else if (section.type === 'cards') {
      html += field(section, 'title', 'Heading') + field(section, 'text', 'Cards, one per line: Title|Description', 'textarea') +
        '<label>Columns</label><input data-rpsb-field="columns" type="number" min="1" max="4" value="' + escapeAttr(section.columns || 3) + '">' +
        field(section, 'background', 'Background') + field(section, 'align', 'Alignment') + field(section, 'padding', 'Padding');
    } else if (section.type === 'cta') {
      html += field(section, 'title', 'Heading') + field(section, 'text', 'Text', 'textarea') + field(section, 'button_label', 'Button label') + field(section, 'button_url', 'Button URL', 'url') +
        field(section, 'theme', 'Theme') + field(section, 'align', 'Alignment') + field(section, 'padding', 'Padding');
    } else if (section.type === 'shortcode') {
      html += field(section, 'title', 'Label') + field(section, 'shortcode', 'Shortcode', 'textarea');
    } else if (section.type === 'component') {
      html += field(section, 'title', 'Label') + field(section, 'component_id', 'Component post ID', 'number');
    }

    html += '</div><footer><button type="button" class="button" data-rpsb-up>Move up</button><button type="button" class="button" data-rpsb-down>Move down</button></footer></article>';
    return html;
  }

  ready(function () {
    document.querySelectorAll('[data-rpsb-builder]').forEach(function (builder) {
      var input = builder.querySelector('[data-rpsb-layout]');
      var list = builder.querySelector('[data-rpsb-sections]');
      var layout = [];

      try {
        layout = JSON.parse(input.value || '[]');
      } catch (error) {
        layout = [];
      }

      function save() {
        input.value = JSON.stringify(layout);
      }

      function draw() {
        list.innerHTML = layout.length ? layout.map(renderSection).join('') : '<p class="rpsb-empty">No sections yet.</p>';
        list.querySelectorAll('select[data-rpsb-field="background"]').forEach(function (select) {
          var section = layout[select.closest('[data-rpsb-index]').dataset.rpsbIndex];
          select.value = section.background || 'white';
        });
        save();
      }

      builder.addEventListener('click', function (event) {
        var add = event.target.closest('[data-rpsb-add]');
        if (add) {
          var template = window.rpsbAdmin && window.rpsbAdmin.blocks ? window.rpsbAdmin.blocks[add.dataset.rpsbAdd] : null;
          if (template) {
            layout.push(JSON.parse(JSON.stringify(template)));
            draw();
          }
          return;
        }

        var card = event.target.closest('[data-rpsb-index]');
        if (!card) {
          return;
        }
        var index = parseInt(card.dataset.rpsbIndex, 10);

        if (event.target.closest('[data-rpsb-remove]')) {
          layout.splice(index, 1);
          draw();
        } else if (event.target.closest('[data-rpsb-up]') && index > 0) {
          layout.splice(index - 1, 0, layout.splice(index, 1)[0]);
          draw();
        } else if (event.target.closest('[data-rpsb-down]') && index < layout.length - 1) {
          layout.splice(index + 1, 0, layout.splice(index, 1)[0]);
          draw();
        }
      });

      function updateField(event) {
        var inputField = event.target.closest('[data-rpsb-field]');
        if (!inputField) {
          return;
        }
        var card = inputField.closest('[data-rpsb-index]');
        layout[parseInt(card.dataset.rpsbIndex, 10)][inputField.dataset.rpsbField] = inputField.value;
        save();
      }

      builder.addEventListener('input', updateField);
      builder.addEventListener('change', updateField);

      draw();
    });
  });
})();
