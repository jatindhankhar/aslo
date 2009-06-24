UPDATE collections AS c LEFT JOIN translations AS t ON (c.name = t.id AND t.locale = 'en-US') SET c.defaultlocale = (SELECT locale FROM translations AS tl WHERE tl.id = c.name LIMIT 1) WHERE t.id IS NULL;
