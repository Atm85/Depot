# Shop GUI

Easily add or remove items within the server in-game store!

1 - `Edit shop.yml` (there are items set by default)

2 - `Catagories are the first layer`

```yaml
Category1:
- item1
- item2
Category2:
- item1
- item2
- item3
```

3 - `items are formted in a sring... (itemid:itemmeta-buyprice-itemname)`

example:
```yaml
Catrgory:
- '1-75-Stone'
```

4 - `if the item doent have a meta or the meta is '0'... you can leave it out as seen in the example`

5 - `run command '/shop' to open shop gui`