<?php
	/**
	 * language pack
	 * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	define('DATE_TIME_FORMAT', 'd.m.Y H:i:s');
	//Common
	//Menu
	
	
	
	
	define('MENU_SELECT', 'Выбрать');
	define('MENU_DOWNLOAD', 'Скачать');
	define('MENU_PREVIEW', 'Посмотреть');
	define('MENU_RENAME', 'Переименовать');
	define('MENU_EDIT', 'Редактировать');
	define('MENU_CUT', 'Вырезать');
	define('MENU_COPY', 'Копировать');
	define('MENU_DELETE', 'Удалить');
	define('MENU_PLAY', 'Воспроизвести');
	define('MENU_PASTE', 'Вставить');
	
	//Label
		//Top Action
		define('LBL_ACTION_REFRESH', 'Обновить');
		define('LBL_ACTION_DELETE', 'Удалить');
		define('LBL_ACTION_CUT', 'Вырезать');
		define('LBL_ACTION_COPY', 'Копировать');
		define('LBL_ACTION_PASTE', 'Вставить');
		define('LBL_ACTION_CLOSE', 'Закрыть');
		define('LBL_ACTION_SELECT_ALL', 'Выбрать Все');
		//File Listing
	define('LBL_NAME', 'Имя');
	define('LBL_SIZE', 'Размер');
	define('LBL_MODIFIED', 'Изменен');
		//File Information
	define('LBL_FILE_INFO', 'Информация о файле:');
	define('LBL_FILE_NAME', 'Имя:');	
	define('LBL_FILE_CREATED', 'Создан:');
	define('LBL_FILE_MODIFIED', 'Изменен:');
	define('LBL_FILE_SIZE', 'Размер файла:');
	define('LBL_FILE_TYPE', 'Тип файла:');
	define('LBL_FILE_WRITABLE', 'Запись?');
	define('LBL_FILE_READABLE', 'Чтение?');
		//Folder Information
	define('LBL_FOLDER_INFO', 'Информация о папке');
	define('LBL_FOLDER_PATH', 'Папка:');
	define('LBL_CURRENT_FOLDER_PATH', 'Путь текущей папки:');
	define('LBL_FOLDER_CREATED', 'Создана:');
	define('LBL_FOLDER_MODIFIED', 'Изменена:');
	define('LBL_FOLDER_SUDDIR', 'Подпапки:');
	define('LBL_FOLDER_FIELS', 'Файлы:');
	define('LBL_FOLDER_WRITABLE', 'Запись?');
	define('LBL_FOLDER_READABLE', 'Чтение?');
	define('LBL_FOLDER_ROOT', 'Корневая папка');
		//Preview
	define('LBL_PREVIEW', 'Просмотр');
	define('LBL_CLICK_PREVIEW', 'Щелкните для просмотра');
	//Buttons
	define('LBL_BTN_SELECT', 'Выбрать');
	define('LBL_BTN_CANCEL', 'Отмена');
	define('LBL_BTN_UPLOAD', 'Загрузить');
	define('LBL_BTN_CREATE', 'Создать');
	define('LBL_BTN_CLOSE', 'Закрыть');
	define('LBL_BTN_NEW_FOLDER', 'Новая папка');
	define('LBL_BTN_NEW_FILE', 'Новый файл');
	define('LBL_BTN_EDIT_IMAGE', 'Редактировать');
	define('LBL_BTN_VIEW', 'Режим просмотра');
	define('LBL_BTN_VIEW_TEXT', 'Список');
	define('LBL_BTN_VIEW_DETAILS', 'Таблица');
	define('LBL_BTN_VIEW_THUMBNAIL', 'Иконки');
	define('LBL_BTN_VIEW_OPTIONS', 'Посмотреть в:');
	//pagination
	define('PAGINATION_NEXT', 'След.');
	define('PAGINATION_PREVIOUS', 'Пред.');
	define('PAGINATION_LAST', 'Последн.');
	define('PAGINATION_FIRST', 'Первая');
	define('PAGINATION_ITEMS_PER_PAGE', 'По %s на странице');
	define('PAGINATION_GO_PARENT', 'Наверх');
	//System
	define('SYS_DISABLED', 'Отказано в доступе: The system is disabled.');
	
	//Cut
	define('ERR_NOT_DOC_SELECTED_FOR_CUT', 'Нет выделенных документов.');
	//Copy
	define('ERR_NOT_DOC_SELECTED_FOR_COPY', 'Нет выделенных документов.');
	//Paste
	define('ERR_NOT_DOC_SELECTED_FOR_PASTE', 'Нет выделенных документов.');
	define('WARNING_CUT_PASTE', 'Вы уверены в перемещении выделенных документов?');
	define('WARNING_COPY_PASTE', 'Вы уверены в копировании выделенных документов?');
	define('ERR_NOT_DEST_FOLDER_SPECIFIED', 'Не указана папка назначения.');
	define('ERR_DEST_FOLDER_NOT_FOUND', 'Папка назначения не найдена.');
	define('ERR_DEST_FOLDER_NOT_ALLOWED', 'Нет доступа для перемещения файлов в эту папку');
	define('ERR_UNABLE_TO_MOVE_TO_SAME_DEST', 'Не удалось переместить файл (%s): Начальный путь совпадает с конечным.');
	define('ERR_UNABLE_TO_MOVE_NOT_FOUND', 'Не удалось переместить файл (%s): Начальный файл отсутствует.');
	define('ERR_UNABLE_TO_MOVE_NOT_ALLOWED', 'Не удалось переместить файл (%s): Нет жоступа к начальному файлу.');
 
	define('ERR_NOT_FILES_PASTED', 'Файл(ы) не были вставлены.');

	//Search
	define('LBL_SEARCH', 'Поиск');
	define('LBL_SEARCH_NAME', 'Полный/Часть имени файла:');
	define('LBL_SEARCH_FOLDER', 'Смотреть в:');
	define('LBL_SEARCH_QUICK', 'Быстрый поиск');
	define('LBL_SEARCH_MTIME', 'Время изменения файла (диапазон):');
	define('LBL_SEARCH_SIZE', 'Размер файла:');
	define('LBL_SEARCH_ADV_OPTIONS', 'Доп. настройки');
	define('LBL_SEARCH_FILE_TYPES', 'Типы файла:');
	define('SEARCH_TYPE_EXE', 'Приложение');
	
	define('SEARCH_TYPE_IMG', 'Картинка');
	define('SEARCH_TYPE_ARCHIVE', 'Архив');
	define('SEARCH_TYPE_HTML', 'HTML');
	define('SEARCH_TYPE_VIDEO', 'Видео');
	define('SEARCH_TYPE_MOVIE', 'Видео');
	define('SEARCH_TYPE_MUSIC', 'Музыка');
	define('SEARCH_TYPE_FLASH', 'Flash');
	define('SEARCH_TYPE_PPT', 'Презентация');
	define('SEARCH_TYPE_DOC', 'Документ');
	define('SEARCH_TYPE_WORD', 'Word');
	define('SEARCH_TYPE_PDF', 'PDF');
	define('SEARCH_TYPE_EXCEL', 'Excel');
	define('SEARCH_TYPE_TEXT', 'Текст');
	define('SEARCH_TYPE_UNKNOWN', 'Неизвестный тип');
	define('SEARCH_TYPE_XML', 'XML');
	define('SEARCH_ALL_FILE_TYPES', 'Все типы файлов');
	define('LBL_SEARCH_RECURSIVELY', 'Рекурсивный поиск:');
	define('LBL_RECURSIVELY_YES', 'Да');
	define('LBL_RECURSIVELY_NO', 'Нет');
	define('BTN_SEARCH', 'Начать поиск');
	//thickbox
	define('THICKBOX_NEXT', 'След.&gt;');
	define('THICKBOX_PREVIOUS', '&lt;Пред.');
	define('THICKBOX_CLOSE', 'Закрыть');
	//Calendar
	define('CALENDAR_CLOSE', 'Закрыть');
	define('CALENDAR_CLEAR', 'Очистить');
	define('CALENDAR_PREVIOUS', '&lt;Пред.');
	define('CALENDAR_NEXT', 'След.&gt;');
	define('CALENDAR_CURRENT', 'Сегодня');
	define('CALENDAR_MON', 'Пн');
	define('CALENDAR_TUE', 'Вт');
	define('CALENDAR_WED', 'Ср');
	define('CALENDAR_THU', 'Чт');
	define('CALENDAR_FRI', 'Пт');
	define('CALENDAR_SAT', 'Сб');
	define('CALENDAR_SUN', 'Вс');
	define('CALENDAR_JAN', 'Янв');
	define('CALENDAR_FEB', 'Фев');
	define('CALENDAR_MAR', 'Мар');
	define('CALENDAR_APR', 'Апр');
	define('CALENDAR_MAY', 'Май');
	define('CALENDAR_JUN', 'Июн');
	define('CALENDAR_JUL', 'Июл');
	define('CALENDAR_AUG', 'Авг');
	define('CALENDAR_SEP', 'Сен');
	define('CALENDAR_OCT', 'Окт');
	define('CALENDAR_NOV', 'Ноя');
	define('CALENDAR_DEC', 'Дек');
	//ERROR MESSAGES
		//deletion
	define('ERR_NOT_FILE_SELECTED', 'Выберите файл.');
	define('ERR_NOT_DOC_SELECTED', 'Нет выбранных документов.');
	define('ERR_DELTED_FAILED', 'Невозможно удалить выбранные документы.');
	define('ERR_FOLDER_PATH_NOT_ALLOWED', 'Недопустимый путь.');
		//class manager
	define('ERR_FOLDER_NOT_FOUND', 'Не удалось определить папку: ');
		//rename
	define('ERR_RENAME_FORMAT', 'Допустимы только символы лат. алфавита и цифры.');
	define('ERR_RENAME_EXISTS', 'Укажите уникальное имя.');
	define('ERR_RENAME_FILE_NOT_EXISTS', 'Файл/папка не существует.');
	define('ERR_RENAME_FAILED', 'Не получилось переименовать, попробуйте позже.');
	define('ERR_RENAME_EMPTY', 'Укажите имя.');
	define('ERR_NO_CHANGES_MADE', 'Не было сделано изменений.');
	define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Данное расширение запрещено настройками.');
		//folder creation
	define('ERR_FOLDER_FORMAT', 'Допустимы только символы лат. алфавита и цифры.');
	define('ERR_FOLDER_EXISTS', 'Укажите уникальное имя.');
	define('ERR_FOLDER_CREATION_FAILED', 'Не удалось создать папку, попробуйте позже.');
	define('ERR_FOLDER_NAME_EMPTY', 'Укажите имя.');
	define('FOLDER_FORM_TITLE', 'Создание новой папки');
	define('FOLDER_LBL_TITLE', 'Название папки:');
	define('FOLDER_LBL_CREATE', 'Создать папку');
	//New File
	define('NEW_FILE_FORM_TITLE', 'Создание нового файла');
	define('NEW_FILE_LBL_TITLE', 'Имя файла:');
	define('NEW_FILE_CREATE', 'Создать файл');
		//file upload
	define('ERR_FILE_NAME_FORMAT', 'Допустимы только символы лат. алфавита и цифры.');
	define('ERR_FILE_NOT_UPLOADED', 'Не выбран файл для загрузки.');
	define('ERR_FILE_TYPE_NOT_ALLOWED', 'Запрещена загрузка файлов данного типа.');
	define('ERR_FILE_MOVE_FAILED', 'Не удалось переместить файл.');
	define('ERR_FILE_NOT_AVAILABLE', 'Файл недоступен.');
	define('ERROR_FILE_TOO_BID', 'Файл слишком большой. (макс.: %s)');
	define('FILE_FORM_TITLE', 'Загрузка файла');
	define('FILE_LABEL_SELECT', 'Выбор файла');
	define('FILE_LBL_MORE', 'Добавить файл');
	define('FILE_CANCEL_UPLOAD', 'Отменить');
	define('FILE_LBL_UPLOAD', 'Загрузить');
	//file download
	define('ERR_DOWNLOAD_FILE_NOT_FOUND', 'Не выбраны файлы для скачивания.');
	//Rename
	define('RENAME_FORM_TITLE', 'Переименование');
	define('RENAME_NEW_NAME', 'Новое имя');
	define('RENAME_LBL_RENAME', 'Переименовать');

	//Tips
	define('TIP_FOLDER_GO_DOWN', 'Кликните для перехода в папку...');
	define('TIP_DOC_RENAME', 'Двойной клик для редактирования...');
	define('TIP_FOLDER_GO_UP', 'Кликните для перехода в родительскую папку...');
	define('TIP_SELECT_ALL', 'Выбрать все');
	define('TIP_UNSELECT_ALL', 'Снять выделение');
	//WARNING
	define('WARNING_DELETE', 'Вы действительно хотите удалить выбранные документы?');
	define('WARNING_IMAGE_EDIT', 'Выберите картинку для редактирования.');
	define('WARNING_NOT_FILE_EDIT', 'Выберите файл для редактирования.');
	define('WARING_WINDOW_CLOSE', 'Закрыть окно?');
	//Preview
	define('PREVIEW_NOT_PREVIEW', 'Просмотр невозможен.');
	define('PREVIEW_OPEN_FAILED', 'Не удалось открыть файл.');
	define('PREVIEW_IMAGE_LOAD_FAILED', 'Не удалось загрузить картинку');

	//Login
	define('LOGIN_PAGE_TITLE', 'Ajax File Manager Вход');
	define('LOGIN_FORM_TITLE', 'Вход');
	define('LOGIN_USERNAME', 'Имя:');
	define('LOGIN_PASSWORD', 'Пароль:');
	define('LOGIN_FAILED', 'Неверные имя/пароль.');
	
	
	//88888888888   Below for Image Editor   888888888888888888888
		//Warning 
		define('IMG_WARNING_NO_CHANGE_BEFORE_SAVE', 'You have not made any changes to the images.');
		
		//General
		define('IMG_GEN_IMG_NOT_EXISTS', 'Image does not exist');
		define('IMG_WARNING_LOST_CHANAGES', 'All unsaved changes made to the image will lost, are you sure you wish to continue?');
		define('IMG_WARNING_REST', 'All unsaved changes made to the image will be lost, are you sure to reset?');
		define('IMG_WARNING_EMPTY_RESET', 'No changes has been made to the image so far');
		define('IMG_WARING_WIN_CLOSE', 'Are you sure to close the window?');
		define('IMG_WARNING_UNDO', 'Are you sure to restore the image to previous state?');
		define('IMG_WARING_FLIP_H', 'Are you sure to flip the image horizotally?');
		define('IMG_WARING_FLIP_V', 'Are you sure to flip the image vertically');
		define('IMG_INFO', 'Image Information');
		
		//Mode
			define('IMG_MODE_RESIZE', 'Resize:');
			define('IMG_MODE_CROP', 'Crop:');
			define('IMG_MODE_ROTATE', 'Rotate:');
			define('IMG_MODE_FLIP', 'Flip:');		
		//Button
		
			define('IMG_BTN_ROTATE_LEFT', '90&deg;CCW');
			define('IMG_BTN_ROTATE_RIGHT', '90&deg;CW');
			define('IMG_BTN_FLIP_H', 'Flip Horizontal');
			define('IMG_BTN_FLIP_V', 'Flip Vertical');
			define('IMG_BTN_RESET', 'Reset');
			define('IMG_BTN_UNDO', 'Undo');
			define('IMG_BTN_SAVE', 'Save');
			define('IMG_BTN_CLOSE', 'Close');
			define('IMG_BTN_SAVE_AS', 'Save As');
			define('IMG_BTN_CANCEL', 'Cancel');
		//Checkbox
			define('IMG_CHECKBOX_CONSTRAINT', 'Constraint?');
		//Label
			define('IMG_LBL_WIDTH', 'Width:');
			define('IMG_LBL_HEIGHT', 'Height:');
			define('IMG_LBL_X', 'X:');
			define('IMG_LBL_Y', 'Y:');
			define('IMG_LBL_RATIO', 'Ratio:');
			define('IMG_LBL_ANGLE', 'Angle:');
			define('IMG_LBL_NEW_NAME', 'New Name:');
			define('IMG_LBL_SAVE_AS', 'Save As Form');
			define('IMG_LBL_SAVE_TO', 'Save To:');
			define('IMG_LBL_ROOT_FOLDER', 'Root Folder');
		//Editor
		//Save as 
		define('IMG_NEW_NAME_COMMENTS', 'Please do not contain the image extension.');
		define('IMG_SAVE_AS_ERR_NAME_INVALID', 'Please give it a name which only contain letters, digits, space, hyphen and underscore.');
		define('IMG_SAVE_AS_NOT_FOLDER_SELECTED', 'No distination folder selected.');	
		define('IMG_SAVE_AS_FOLDER_NOT_FOUND', 'The destination folder doest not exist.');
		define('IMG_SAVE_AS_NEW_IMAGE_EXISTS', 'There exists an image with same name.');

		//Save
		define('IMG_SAVE_EMPTY_PATH', 'Empty image path.');
		define('IMG_SAVE_NOT_EXISTS', 'Image does not exist.');
		define('IMG_SAVE_PATH_DISALLOWED', 'You are not allowed to access this file.');
		define('IMG_SAVE_UNKNOWN_MODE', 'Unexpected Image Operation Mode');
		define('IMG_SAVE_RESIZE_FAILED', 'Failed to resize the image.');
		define('IMG_SAVE_CROP_FAILED', 'Failed to crop the image.');
		define('IMG_SAVE_FAILED', 'Failed to save the image.');
		define('IMG_SAVE_BACKUP_FAILED', 'Unable to backup the original image.');
		define('IMG_SAVE_ROTATE_FAILED', 'Unable to rotate the image.');
		define('IMG_SAVE_FLIP_FAILED', 'Unable to flip the image.');
		define('IMG_SAVE_SESSION_IMG_OPEN_FAILED', 'Unable to open image from session.');
		define('IMG_SAVE_IMG_OPEN_FAILED', 'Unable to open image');
		
		
		//UNDO
		define('IMG_UNDO_NO_HISTORY_AVAIALBE', 'No history avaiable for undo.');
		define('IMG_UNDO_COPY_FAILED', 'Unable to restore the image.');
		define('IMG_UNDO_DEL_FAILED', 'Unable to delete the session image');
	
	//88888888888   Above for Image Editor   888888888888888888888
	
	//88888888888   Session   888888888888888888888
		define('SESSION_PERSONAL_DIR_NOT_FOUND', 'Unable to find the dedicated folder which should have been created under session folder');
		define('SESSION_COUNTER_FILE_CREATE_FAILED', 'Unable to open the session counter file.');
		define('SESSION_COUNTER_FILE_WRITE_FAILED', 'Unable to write the session counter file.');
	//88888888888   Session   888888888888888888888
	
	//88888888888   Below for Text Editor   888888888888888888888
		define('TXT_FILE_NOT_FOUND', 'File is not found.');
		define('TXT_EXT_NOT_SELECTED', 'Please select file extension');
		define('TXT_DEST_FOLDER_NOT_SELECTED', 'Please select destination folder');
		define('TXT_UNKNOWN_REQUEST', 'Unknown Request.');
		define('TXT_DISALLOWED_EXT', 'You are allowed to edit/add such file type.');
		define('TXT_FILE_EXIST', 'Such file already exits.');
		define('TXT_FILE_NOT_EXIST', 'No such found.');
		define('TXT_CREATE_FAILED', 'Failed to create a new file.');
		define('TXT_CONTENT_WRITE_FAILED', 'Failed to write content to the file.');
		define('TXT_FILE_OPEN_FAILED', 'Failed to open the file.');
		define('TXT_CONTENT_UPDATE_FAILED', 'Failed to update content to the file.');
		define('TXT_SAVE_AS_ERR_NAME_INVALID', 'Please give it a name which only contain letters, digits, space, hyphen and underscore.');
	//88888888888   Above for Text Editor   888888888888888888888
	
	
?>