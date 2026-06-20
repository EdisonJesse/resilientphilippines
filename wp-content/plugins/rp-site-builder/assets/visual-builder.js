(function () {
  function clone(value) {
    return JSON.parse(JSON.stringify(value));
  }

  function html(value) {
    return String(value || '').replace(/[&<>"']/g, function (char) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[char];
    });
  }

  function rich(value) {
    return html(value).replace(/\n/g, '<br>');
  }

  function decodeEntities(value) {
    var textarea = document.createElement('textarea');
    textarea.innerHTML = String(value || '');
    return textarea.value;
  }

  function text(value) {
    return decodeEntities(value).replace(/\s+/g, ' ').trim();
  }

  function button(section) {
    if (!section.button_label || !section.button_url) {
      return '';
    }
    return '<a class="rp-button" href="' + html(section.button_url) + '">' + html(section.button_label) + '</a>';
  }

  function sectionClasses(base, section) {
    return [
      base,
      section.background ? 'rpsb-section-' + section.background : '',
      section.theme ? 'rpsb-theme-' + section.theme : '',
      section.align ? 'rpsb-align-' + section.align : '',
      section.width ? 'rpsb-width-' + section.width : '',
      section.padding ? 'rpsb-padding-' + section.padding : ''
    ].filter(Boolean).join(' ');
  }

  function renderPreview(section, index, selected) {
    var selectedClass = selected ? ' is-selected' : '';
    var tools = '<div class="rpsb-vb-section-tools"><button type="button" data-rpsb-up>Up</button><button type="button" data-rpsb-down>Down</button><button type="button" data-rpsb-duplicate>Duplicate</button><button type="button" data-rpsb-remove>Remove</button></div>';
    var data = ' data-rpsb-section="' + index + '"';

    if (section.type === 'hero') {
      return '<section' + data + ' class="' + sectionClasses('rpsb-vb-section rpsb-hero', section) + selectedClass + '">' +
        tools + (section.image_url ? '<img src="' + html(section.image_url) + '" alt="' + html(section.image_alt) + '">' : '') +
        '<div class="rp-section-inner rpsb-hero-content"><div class="rpsb-hero-copy">' +
        (section.eyebrow ? '<p class="rp-eyebrow" contenteditable data-rpsb-inline="eyebrow">' + html(section.eyebrow) + '</p>' : '') +
        '<h2 contenteditable data-rpsb-inline="title">' + html(section.title || 'Hero headline') + '</h2>' +
        '<p contenteditable data-rpsb-inline="text">' + rich(section.text || '') + '</p>' + button(section) +
        '</div></div></section>';
    }

    if (section.type === 'text') {
      return '<section' + data + ' class="' + sectionClasses('rpsb-vb-section rpsb-section', section) + selectedClass + '">' + tools +
        '<div class="rp-section-inner"><h2 contenteditable data-rpsb-inline="title">' + html(section.title || 'Section heading') + '</h2>' +
        '<div class="rpsb-rich-text" contenteditable data-rpsb-inline="text">' + rich(section.text || '') + '</div></div></section>';
    }

    if (section.type === 'image_text') {
      return '<section' + data + ' class="' + sectionClasses('rpsb-vb-section rpsb-section rpsb-image-text-section', section) + selectedClass + '">' + tools +
        '<div class="rp-section-inner rpsb-image-text"><div class="rpsb-image-text-media">' +
        (section.image_url ? '<img src="' + html(section.image_url) + '" alt="' + html(section.image_alt) + '">' : '<div class="rpsb-vb-image-placeholder">Image</div>') +
        '</div><div class="rpsb-image-text-copy">' +
        '<p class="rp-eyebrow" contenteditable data-rpsb-inline="kicker">' + html(section.kicker || 'Featured') + '</p>' +
        '<h2 contenteditable data-rpsb-inline="title">' + html(section.title || 'Image and text') + '</h2>' +
        '<div class="rpsb-rich-text" contenteditable data-rpsb-inline="text">' + rich(section.text || '') + '</div>' + button(section) +
        '</div></div></section>';
    }

    if (section.type === 'image') {
      return '<section' + data + ' class="' + sectionClasses('rpsb-vb-section rpsb-section rpsb-image-section', section) + selectedClass + '">' + tools +
        '<div class="rp-section-inner"><figure class="rpsb-image-figure">' +
        (section.image_url ? '<img src="' + html(section.image_url) + '" alt="' + html(section.image_alt) + '">' : '<div class="rpsb-vb-image-placeholder">Image</div>') +
        (section.caption ? '<figcaption contenteditable data-rpsb-inline="caption">' + html(section.caption) + '</figcaption>' : '') +
        '</figure></div></section>';
    }

    if (section.type === 'cards') {
      var rows = String(section.text || '').split(/\r?\n/).filter(Boolean);
      var cards = rows.map(function (row) {
        var parts = row.split('|');
        return '<article class="rp-card rpsb-card"><h3>' + html(parts[0]) + '</h3><p>' + html(parts.slice(1).join('|')) + '</p></article>';
      }).join('');
      return '<section' + data + ' class="' + sectionClasses('rpsb-vb-section rpsb-section rpsb-cards-section', section) + selectedClass + '">' + tools +
        '<div class="rp-section-inner"><h2 contenteditable data-rpsb-inline="title">' + html(section.title || 'Cards') + '</h2>' +
        '<div class="rpsb-card-grid" style="--rpsb-columns:' + html(section.columns || 3) + '">' + cards + '</div></div></section>';
    }

    if (section.type === 'cta') {
      return '<section' + data + ' class="' + sectionClasses('rpsb-vb-section rpsb-cta', section) + selectedClass + '">' + tools +
        '<div class="rp-section-inner"><h2 contenteditable data-rpsb-inline="title">' + html(section.title || 'Call to action') + '</h2>' +
        '<p contenteditable data-rpsb-inline="text">' + rich(section.text || '') + '</p>' + button(section) + '</div></section>';
    }

    if (section.type === 'shortcode') {
      return '<section' + data + ' class="rpsb-vb-section rpsb-vb-utility' + selectedClass + '">' + tools +
        '<strong>' + html(section.title || 'Shortcode') + '</strong><code>' + html(section.shortcode || '[shortcode]') + '</code></section>';
    }

    if (section.type === 'html') {
      return '<section' + data + ' class="rpsb-vb-section rpsb-section rpsb-html-section' + selectedClass + '">' + tools +
        '<div class="rp-section-inner">' + (section.html || '<div class="rpsb-vb-utility">HTML block</div>') + '</div></section>';
    }

    return '<section' + data + ' class="rpsb-vb-section rpsb-vb-utility' + selectedClass + '">' + tools +
      '<strong>' + html(section.title || 'Component') + '</strong><span>Component ID: ' + html(section.component_id || '') + '</span></section>';
  }

  function input(label, key, value, type) {
    return '<label><span>' + label + '</span><input data-rpsb-field="' + key + '" type="' + (type || 'text') + '" value="' + html(value || '') + '"></label>';
  }

  function textarea(label, key, value) {
    return '<label><span>' + label + '</span><textarea data-rpsb-field="' + key + '" rows="5">' + html(value || '') + '</textarea></label>';
  }

  function select(label, key, value, options) {
    return '<label><span>' + label + '</span><select data-rpsb-field="' + key + '">' + options.map(function (item) {
      return '<option value="' + html(item[0]) + '"' + (value === item[0] ? ' selected' : '') + '>' + html(item[1]) + '</option>';
    }).join('') + '</select></label>';
  }

  function commonControls(section) {
    return select('Padding', 'padding', section.padding || 'default', [['compact', 'Compact'], ['default', 'Default'], ['spacious', 'Spacious']]) +
      select('Alignment', 'align', section.align || 'left', [['left', 'Left'], ['center', 'Center'], ['right', 'Right']]);
  }

  function inspector(section, components) {
    if (!section) {
      return '<p>Select a section on the canvas.</p>';
    }

    var controls = '<div class="rpsb-inspector-fields">' + input('Admin label', 'title', section.title);

    if (section.type === 'hero') {
      controls += input('Eyebrow', 'eyebrow', section.eyebrow) + textarea('Intro text', 'text', section.text) + input('Button label', 'button_label', section.button_label) + input('Button URL', 'button_url', section.button_url, 'url') +
        input('Image URL', 'image_url', section.image_url, 'url') + '<button type="button" class="button" data-rpsb-media="image_url">Choose Image</button>' +
        input('Image alt', 'image_alt', section.image_alt) + select('Theme', 'theme', section.theme || 'navy', [['navy', 'Navy'], ['green', 'Green'], ['white', 'White']]) + commonControls(section);
    } else if (section.type === 'text') {
      controls += textarea('Body copy', 'text', section.text) + select('Background', 'background', section.background || 'white', [['white', 'White'], ['soft', 'Soft'], ['navy', 'Navy'], ['green', 'Green']]) +
        select('Width', 'width', section.width || 'contained', [['contained', 'Contained'], ['wide', 'Wide'], ['full', 'Full']]) + commonControls(section);
    } else if (section.type === 'image_text') {
      controls += input('Kicker', 'kicker', section.kicker) + textarea('Body copy', 'text', section.text) + input('Button label', 'button_label', section.button_label) + input('Button URL', 'button_url', section.button_url, 'url') +
        input('Image URL', 'image_url', section.image_url, 'url') + '<button type="button" class="button" data-rpsb-media="image_url">Choose Image</button>' +
        input('Image alt', 'image_alt', section.image_alt) + select('Background', 'background', section.background || 'white', [['white', 'White'], ['soft', 'Soft']]) + commonControls(section);
    } else if (section.type === 'image') {
      controls += input('Image URL', 'image_url', section.image_url, 'url') + '<button type="button" class="button" data-rpsb-media="image_url">Choose Image</button>' +
        input('Image alt', 'image_alt', section.image_alt) + input('Caption', 'caption', section.caption) +
        select('Background', 'background', section.background || 'white', [['white', 'White'], ['soft', 'Soft']]) +
        select('Width', 'width', section.width || 'wide', [['contained', 'Contained'], ['wide', 'Wide'], ['full', 'Full']]) + commonControls(section);
    } else if (section.type === 'cards') {
      controls += textarea('Cards: Title|Description', 'text', section.text) + input('Columns', 'columns', section.columns || 3, 'number') +
        select('Background', 'background', section.background || 'soft', [['white', 'White'], ['soft', 'Soft']]) + commonControls(section);
    } else if (section.type === 'cta') {
      controls += textarea('Text', 'text', section.text) + input('Button label', 'button_label', section.button_label) + input('Button URL', 'button_url', section.button_url, 'url') +
        select('Theme', 'theme', section.theme || 'navy', [['navy', 'Navy'], ['green', 'Green']]) + commonControls(section);
    } else if (section.type === 'shortcode') {
      controls += textarea('Shortcode', 'shortcode', section.shortcode);
    } else if (section.type === 'html') {
      controls += textarea('HTML', 'html', section.html);
    } else if (section.type === 'component') {
      var choices = [['0', 'Choose component']].concat((components || []).map(function (component) {
        return [String(component.id), component.title];
      }));
      controls += select('Component', 'component_id', String(section.component_id || 0), choices);
    }

    return controls + '</div>';
  }

  function structure(layout, selected) {
    if (!layout.length) {
      return '<p>No sections yet.</p>';
    }
    return layout.map(function (section, index) {
      return '<button type="button" class="' + (selected === index ? 'is-active' : '') + '" data-rpsb-structure-item="' + index + '">' + (index + 1) + '. ' + html(section.title || section.type) + '</button>';
    }).join('');
  }

  function textFromEditable(node) {
    return node.innerText.replace(/\n{3,}/g, '\n\n').trim();
  }

  function init(builder) {
    var pageId = builder.dataset.pageId;
    var layout = [];
    var selected = 0;
    var canvas = builder.querySelector('[data-rpsb-canvas]');
    var inspectorNode = builder.querySelector('[data-rpsb-inspector]');
    var structureNode = builder.querySelector('[data-rpsb-structure]');
    var statusNode = builder.querySelector('[data-rpsb-save-status]');
    var canvasWrap = builder.querySelector('[data-rpsb-device-wrap]');
    var liveFrame = builder.querySelector('[data-rpsb-live-frame]');
    var sourceNode = builder.querySelector('[data-rpsb-page-source]');
    var pageSource = {};

    try {
      layout = JSON.parse(builder.dataset.layout || '[]');
    } catch (error) {
      layout = [];
    }

    try {
      pageSource = JSON.parse(sourceNode ? sourceNode.textContent : '{}');
    } catch (error) {
      pageSource = {};
    }

    function markDirty() {
      statusNode.textContent = 'Unsaved changes';
      statusNode.classList.add('is-dirty');
    }

    function setMode(mode) {
      canvasWrap.dataset.rpsbMode = mode;
      canvasWrap.classList.toggle('is-live-mode', mode === 'live');
      canvasWrap.classList.toggle('is-builder-mode', mode !== 'live');
      canvas.style.display = mode === 'live' ? 'none' : '';
      if (liveFrame) {
        liveFrame.style.display = mode === 'live' ? 'block' : 'none';
      }
    }

    function draw() {
      if (!layout.length) {
        canvas.innerHTML = '<div class="rpsb-vb-empty"><strong>This page has no builder sections yet.</strong><p>Use Live Page to see the current page, import the existing content, or add a new section/template.</p><button type="button" class="button button-primary" data-rpsb-import-current>Import Current Content</button></div>';
      } else {
        canvas.innerHTML = layout.map(function (section, index) {
          return renderPreview(section, index, selected === index);
        }).join('');
      }
      inspectorNode.innerHTML = inspector(layout[selected], window.rpsbAdmin.components);
      structureNode.innerHTML = structure(layout, selected);
    }

    function addBlock(type) {
      var block = window.rpsbAdmin.blocks[type];
      if (!block) {
        return;
      }
      layout.splice(selected + 1, 0, clone(block));
      selected = Math.min(selected + 1, layout.length - 1);
      markDirty();
      draw();
    }

    function importCurrentContent() {
      var raw = importRawContent();
      if (raw.length) {
        layout = raw;
        selected = 0;
        markDirty();
        draw();
        return;
      }

      var rendered = importRenderedPage();
      if (rendered.length) {
        layout = rendered;
        selected = 0;
        markDirty();
        draw();
        return;
      }

      var blocks = window.rpsbAdmin.blocks;
      var hero = clone(blocks.hero);
      hero.title = text(pageSource.title) || hero.title;
      hero.text = pageSource.content ? text(pageSource.content).slice(0, 220) : hero.text;

      layout = [hero];
      if (pageSource.content) {
        var text = clone(blocks.text);
        text.title = hero.title || 'Page content';
        text.text = decodeEntities(pageSource.content);
        layout.push(text);
      }
      selected = 0;
      markDirty();
      draw();
    }

    function htmlToDocument(rawHtml) {
      if (!rawHtml) {
        return null;
      }
      return new DOMParser().parseFromString('<main>' + rawHtml + '</main>', 'text/html');
    }

    function directChildrenWithContent(root) {
      return Array.prototype.slice.call(root.children).filter(function (node) {
        return text(node.textContent) || node.querySelector('img, iframe, form, table, ul, ol');
      });
    }

    function isGrid(node) {
      var style = node.getAttribute('style') || '';
      return node.tagName === 'DIV' && /display\s*:\s*grid/i.test(style);
    }

    function cardRowsFromGrid(node) {
      return Array.prototype.slice.call(node.children).map(function (child) {
        var titleNode = child.querySelector('h2, h3, h4, strong, b');
        var copyNode = child.querySelector('p, span, li');
        var title = text(titleNode ? titleNode.textContent : child.textContent);
        var copy = text(copyNode ? copyNode.textContent : '');
        return title ? title + '|' + copy : '';
      }).filter(Boolean);
    }

    function importRawContent() {
      var doc = htmlToDocument(pageSource.raw_content);
      if (!doc) {
        return [];
      }

      var blocks = window.rpsbAdmin.blocks;
      var root = doc.querySelector('main');
      var children = directChildrenWithContent(root);
      var imported = [];
      var consumed = new Set();
      var firstHeading = children.find(function (node) {
        return /^H[1-3]$/.test(node.tagName) || (node.tagName === 'P' && node.querySelector('strong') && parseInt((node.getAttribute('style') || '').match(/font-size:\s*(\d+)/i)?.[1] || '0', 10) >= 28);
      });

      if (firstHeading) {
        var hero = clone(blocks.hero);
        hero.title = text(firstHeading.textContent) || text(pageSource.title) || hero.title;
        hero.text = '';
        imported.push(hero);
        consumed.add(firstHeading);
      }

      children.forEach(function (node, index) {
        if (consumed.has(node)) {
          return;
        }

        if (node.querySelector && node.querySelector('img')) {
          var imageBlock = clone(blocks.image);
          var img = node.querySelector('img');
          imageBlock.title = 'Image';
          imageBlock.image_url = img.getAttribute('src') || '';
          imageBlock.image_alt = img.getAttribute('alt') || '';
          imageBlock.caption = text((node.querySelector('figcaption, .wp-caption-text') || {}).textContent || '');
          imported.push(imageBlock);
          consumed.add(node);
          return;
        }

        if (isGrid(node)) {
          var rows = cardRowsFromGrid(node);
          if (rows.length) {
            var heading = children[index - 1] && /^H[1-3]$/.test(children[index - 1].tagName) ? children[index - 1] : null;
            var cards = clone(blocks.cards);
            cards.title = heading ? text(heading.textContent) : rows.length === 2 ? 'Vision and Mission' : 'Cards';
            cards.columns = rows.length === 2 ? 2 : Math.min(4, Math.max(2, rows.length));
            cards.text = rows.join('\n');
            imported.push(cards);
            consumed.add(node);
            if (heading) {
              consumed.add(heading);
            }
          }
          return;
        }

        if (/^H[1-3]$/.test(node.tagName)) {
          if (children[index + 1] && isGrid(children[index + 1])) {
            return;
          }
          var copy = [];
          var next = children[index + 1];
          while (next && !consumed.has(next) && !/^H[1-3]$/.test(next.tagName) && !isGrid(next) && copy.length < 4) {
            if (next.tagName === 'P' || next.tagName === 'UL' || next.tagName === 'OL') {
              copy.push(text(next.textContent));
              consumed.add(next);
            }
            next = children[children.indexOf(next) + 1];
          }
          var textBlock = clone(blocks.text);
          textBlock.title = text(node.textContent);
          textBlock.text = copy.join('\n\n');
          imported.push(textBlock);
          consumed.add(node);
        }

        if (node.tagName === 'P') {
          var section = clone(blocks.text);
          section.title = '';
          section.text = text(node.textContent);
          if (section.text) {
            imported.push(section);
            consumed.add(node);
          }
        }
      });

      var leftover = children.filter(function (node) {
        return !consumed.has(node) && node.outerHTML && text(node.textContent);
      }).map(function (node) {
        return node.outerHTML;
      }).join('\n');

      if (leftover) {
        var htmlBlock = clone(blocks.html);
        htmlBlock.title = 'Imported HTML';
        htmlBlock.html = leftover;
        imported.push(htmlBlock);
      }

      return imported.filter(function (item) {
        return item.title || item.text || item.html || item.image_url;
      });
    }

    function liveDocument() {
      try {
        return liveFrame && liveFrame.contentDocument ? liveFrame.contentDocument : null;
      } catch (error) {
        return null;
      }
    }

    function importRenderedPage() {
      var doc = liveDocument();
      if (!doc) {
        return [];
      }

      var blocks = window.rpsbAdmin.blocks;
      var imported = [];
      var heroNode = doc.querySelector('.rp-page-hero, .rp-hero, header + section');
      var titleNode = heroNode ? heroNode.querySelector('h1, h2') : doc.querySelector('h1');
      var hero = clone(blocks.hero);
      hero.title = text(titleNode ? titleNode.textContent : pageSource.title);
      hero.eyebrow = text(heroNode && heroNode.querySelector('.rp-eyebrow') ? heroNode.querySelector('.rp-eyebrow').textContent : hero.eyebrow);
      hero.text = text(heroNode && heroNode.querySelector('p:not(.rp-eyebrow)') ? heroNode.querySelector('p:not(.rp-eyebrow)').textContent : '');
      var heroImage = heroNode ? heroNode.querySelector('img') : null;
      if (heroImage) {
        hero.image_url = heroImage.currentSrc || heroImage.src || '';
        hero.image_alt = heroImage.alt || '';
      }
      if (hero.title) {
        imported.push(hero);
      }

      var contentRoot = doc.querySelector('.entry-content, .rp-single-content, .rp-page-content, main') || doc.body;
      var cardNodes = Array.prototype.slice.call(contentRoot.querySelectorAll('.rp-card, .rp-resource-card, article'));
      if (cardNodes.length > 1) {
        var cards = clone(blocks.cards);
        cards.title = 'Cards';
        cards.text = cardNodes.slice(0, 12).map(function (card) {
          var cardTitle = text((card.querySelector('h2, h3, h4, a') || card).textContent);
          var cardText = text((card.querySelector('p') || {}).textContent || '');
          return cardTitle ? cardTitle + '|' + cardText : '';
        }).filter(Boolean).join('\n');
        if (cards.text) {
          imported.push(cards);
        }
      }

      var imageTextNode = contentRoot.querySelector('figure img, .wp-block-image img, .alignleft img, .alignright img');
      if (imageTextNode) {
        var imageBlock = clone(blocks.image);
        imageBlock.title = 'Image';
        imageBlock.image_url = imageTextNode.currentSrc || imageTextNode.src || '';
        imageBlock.image_alt = imageTextNode.alt || '';
        imported.push(imageBlock);
      }

      var headings = Array.prototype.slice.call(contentRoot.querySelectorAll('h2, h3')).slice(0, 8);
      if (headings.length) {
        headings.forEach(function (heading) {
          var copy = [];
          var node = heading.nextElementSibling;
          while (node && !/^H[1-3]$/.test(node.tagName) && copy.length < 4) {
            if (/^(P|UL|OL|BLOCKQUOTE)$/.test(node.tagName)) {
              copy.push(text(node.textContent));
            }
            node = node.nextElementSibling;
          }
          var section = clone(blocks.text);
          section.title = text(heading.textContent);
          section.text = copy.join('\n\n');
          if (section.title || section.text) {
            imported.push(section);
          }
        });
      } else {
        var paragraphs = Array.prototype.slice.call(contentRoot.querySelectorAll('p')).map(function (paragraph) {
          return text(paragraph.textContent);
        }).filter(Boolean);
        if (paragraphs.length) {
          var section = clone(blocks.text);
          section.title = text(pageSource.title) || 'Page content';
          section.text = paragraphs.join('\n\n');
          imported.push(section);
        }
      }

      return imported.filter(function (item, index, all) {
        return index < 12 && (item.title || item.text || item.image_url);
      });
    }

    function addTemplate(type) {
      var blocks = window.rpsbAdmin.blocks;
      var next = type === 'content'
        ? [clone(blocks.hero), clone(blocks.text), clone(blocks.image), clone(blocks.cta)]
        : [clone(blocks.hero), clone(blocks.image_text), clone(blocks.cards), clone(blocks.cta)];
      layout = layout.concat(next);
      selected = layout.length - next.length;
      markDirty();
      draw();
    }

    function moveSelected(offset) {
      var next = selected + offset;
      if (next < 0 || next >= layout.length) {
        return;
      }
      layout.splice(next, 0, layout.splice(selected, 1)[0]);
      selected = next;
      markDirty();
      draw();
    }

    builder.addEventListener('click', function (event) {
      var tab = event.target.closest('[data-rpsb-tab]');
      if (tab) {
        builder.querySelectorAll('[data-rpsb-tab], [data-rpsb-panel]').forEach(function (node) {
          node.classList.remove('is-active');
        });
        tab.classList.add('is-active');
        builder.querySelector('[data-rpsb-panel="' + tab.dataset.rpsbTab + '"]').classList.add('is-active');
        return;
      }

      var add = event.target.closest('[data-rpsb-add]');
      if (add) {
        addBlock(add.dataset.rpsbAdd);
        return;
      }

      var template = event.target.closest('[data-rpsb-template]');
      if (template) {
        addTemplate(template.dataset.rpsbTemplate);
        return;
      }

      var stageMode = event.target.closest('[data-rpsb-stage-mode]');
      if (stageMode) {
        builder.querySelectorAll('[data-rpsb-stage-mode]').forEach(function (buttonNode) {
          buttonNode.classList.remove('is-active');
        });
        stageMode.classList.add('is-active');
        setMode(stageMode.dataset.rpsbStageMode);
        return;
      }

      var device = event.target.closest('[data-rpsb-device]');
      if (device) {
        builder.querySelectorAll('[data-rpsb-device]').forEach(function (buttonNode) {
          buttonNode.classList.remove('is-active');
        });
        device.classList.add('is-active');
        canvasWrap.dataset.rpsbDevice = device.dataset.rpsbDevice;
        return;
      }

      if (event.target.closest('[data-rpsb-import-current]')) {
        importCurrentContent();
        return;
      }

      var structureButton = event.target.closest('[data-rpsb-structure-item]');
      if (structureButton) {
        selected = parseInt(structureButton.dataset.rpsbStructureItem, 10);
        draw();
        return;
      }

      var section = event.target.closest('[data-rpsb-section]');
      if (section) {
        selected = parseInt(section.dataset.rpsbSection, 10);

        if (event.target.closest('[data-rpsb-up]')) {
          moveSelected(-1);
        } else if (event.target.closest('[data-rpsb-down]')) {
          moveSelected(1);
        } else if (event.target.closest('[data-rpsb-duplicate]')) {
          layout.splice(selected + 1, 0, clone(layout[selected]));
          selected += 1;
          markDirty();
          draw();
        } else if (event.target.closest('[data-rpsb-remove]')) {
          layout.splice(selected, 1);
          selected = Math.max(0, selected - 1);
          markDirty();
          draw();
        } else {
          draw();
        }
      }

      var media = event.target.closest('[data-rpsb-media]');
      if (media && window.wp && window.wp.media && layout[selected]) {
        var frame = window.wp.media({ title: 'Choose image', multiple: false, library: { type: 'image' } });
        frame.on('select', function () {
          var attachment = frame.state().get('selection').first().toJSON();
          layout[selected][media.dataset.rpsbMedia] = attachment.url;
          if (!layout[selected].image_alt) {
            layout[selected].image_alt = attachment.alt || attachment.title || '';
          }
          markDirty();
          draw();
        });
        frame.open();
      }
    });

    builder.addEventListener('input', function (event) {
      var field = event.target.closest('[data-rpsb-field]');
      if (field && layout[selected]) {
        layout[selected][field.dataset.rpsbField] = field.value;
        markDirty();
        canvas.innerHTML = layout.map(function (section, index) {
          return renderPreview(section, index, selected === index);
        }).join('');
        structureNode.innerHTML = structure(layout, selected);
      }

      var inline = event.target.closest('[data-rpsb-inline]');
      if (inline && layout[selected]) {
        layout[selected][inline.dataset.rpsbInline] = textFromEditable(inline);
        markDirty();
        inspectorNode.innerHTML = inspector(layout[selected], window.rpsbAdmin.components);
        structureNode.innerHTML = structure(layout, selected);
      }
    });

    builder.addEventListener('change', function (event) {
      var field = event.target.closest('[data-rpsb-field]');
      if (field && layout[selected]) {
        layout[selected][field.dataset.rpsbField] = field.value;
        markDirty();
        draw();
      }
    });

    builder.querySelector('[data-rpsb-save]').addEventListener('click', function () {
      var isAlreadyEnabled = builder.dataset.rpsbEnabled === '1';
      var hasRawContent = pageSource.raw_content && pageSource.raw_content.trim() && pageSource.raw_content.indexOf('<!-- Built with Resilient Philippines Site Builder -->') === -1;

      if (!isAlreadyEnabled && hasRawContent) {
        if (!confirm('Warning: Saving this layout will overwrite the existing WordPress page content. If you want to keep it, click Cancel and use "Import Current Content" first. Proceed?')) {
          return;
        }
      }

      var body = new URLSearchParams();
      body.set('action', 'rpsb_save_layout');
      body.set('nonce', window.rpsbAdmin.nonce);
      body.set('page_id', pageId);
      body.set('layout', JSON.stringify(layout));
      statusNode.textContent = 'Saving...';

      fetch(window.rpsbAdmin.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
      }).then(function (response) {
        return response.json();
      }).then(function (result) {
        if (!result.success) {
          throw new Error(result.data && result.data.message ? result.data.message : 'Save failed');
        }
        statusNode.textContent = 'Saved';
        statusNode.classList.remove('is-dirty');
        builder.dataset.rpsbEnabled = '1';
      }).catch(function (error) {
        statusNode.textContent = error.message;
        statusNode.classList.add('is-dirty');
      });
    });

    setMode('builder');
    draw();
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-rpsb-visual-builder]').forEach(init);
  });
})();
