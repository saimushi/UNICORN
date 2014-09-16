package com.unicorn.utilities;

/**
* Logクラスはログの出力制御を行うクラスです
* インターフェースはandroid.util.Logと同一
* static変数で出力するログの種類等を制御します
* IS_PRINT_DEBUG　デバッグログの出力有無(Log.d)
* IS_PRINT_WARNNING　警告の出力有無(Log.w)
* IS_PRINT_ERROR　エラーの出力有無(Log.e)
* IS_PRINT_INFO　インフォの出力有無(Log.i)
* IS_PRINT_VERBOSE　詳細の出力有無(Log.v)
* IS_PRINT_CLASS_AND_METHOD　ログをはいたクラス名、メソッド名の出力有無
* @author　c1363
*/
public class Log {

	private static final String TAG = "Project";
	private static final boolean IS_PRINT_DEBUG = true;
	private static final boolean IS_PRINT_WARNNING = true;
	private static final boolean IS_PRINT_ERROR = true;
	private static final boolean IS_PRINT_INFO = true;
	private static final boolean IS_PRINT_VERBOSE = true;
	private static final boolean IS_PRINT_CLASS_AND_METHOD = true;

	/**
	 * 呼び出し元のクラス名、メソッド名、行数を取得する
	 * @return　クラス名-メソッド名(行数)の文字列
	 */
	private static String getParentClassAndMethod() {
		StackTraceElement[] stackTrace = Thread.currentThread().getStackTrace();
		String className = stackTrace[4].getClassName();
		String methodName = stackTrace[4].getMethodName();
		int lineNumber = stackTrace[4].getLineNumber();
		return String.format("%s-%s(%d)", className, methodName, lineNumber);
	}

	/**
	 * getParentClassAndMethod()で取得した呼び出しもと情報をLog.dで出力します
	 */
	public static void printClassAndMethod() {
		if (IS_PRINT_CLASS_AND_METHOD) {
			android.util.Log.d(TAG, getParentClassAndMethod());
		}
	}

	/**
	 * デバッグログ出力メソッド
	 * @param obj .toString()で文字列に変更して出力されます
	 */
	public static void d(Object obj) {
		if (IS_PRINT_DEBUG) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.d(TAG, getParentClassAndMethod());
			}

			if (obj == null) {
				android.util.Log.d(TAG, "null");
			} else {
				android.util.Log.d(TAG, obj.toString());
			}
		}
	}

	/**
	 * デバッグログ出力メソッド
	 * @param tag　ログ出力する際のタグ
	 * @param mes　ログ内容
	 */
	public static void d(String tag, String mes) {
		if (IS_PRINT_DEBUG) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.d(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.d(tag, mes);
			}
		}
	}

	/**
	 * 警告ログ出力メソッド
	 * @param e　警告内容
	 */
	public static void w(Exception e) {
		if (IS_PRINT_WARNNING) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.w(TAG, getParentClassAndMethod());
			}

			if (e == null) {
				android.util.Log.w(TAG, "null");
			} else {
				android.util.Log.w(TAG, e.getMessage(), e);
			}
		}
	}

	/**
	 * 警告ログ出力メソッド
	 * @param tag　ログ出力する際のタグ
	 * @param mes　ログ内容
	 */
	public static void w(String tag, String mes) {
		if (IS_PRINT_WARNNING) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.w(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.w(tag, mes);
			}
		}
	}

	/**
	 * エラーログ出力メソッド
	 * @param e　エラー内容
	 */
	public static void e(Exception e) {
		if (IS_PRINT_ERROR) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.w(TAG, getParentClassAndMethod());
			}

			if (e == null) {
				android.util.Log.e(TAG, "null");
			} else {
				android.util.Log.e(TAG, e.getMessage(), e);
			}
		}
	}

	/**
	 * エラーログ出力メソッド
	 * @param tag　ログ出力する際のタグ　
	 * @param mes　ログ内容
	 */
	public static void e(String tag, String mes) {
		if (IS_PRINT_ERROR) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.e(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.e(tag, mes);
			}
		}
	}

	/**
	 * エラーログ出力メソッド
	 * @param tag　ログ出力する際のタグ
	 * @param mes　ログ内容
	 * @param e　エラー内容
	 */
	public static void e(String tag, String mes, Exception e) {
		if (IS_PRINT_ERROR) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.e(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.e(tag, mes, e);
			}
		}
	}

	/**
	 * インフォログ出力メソッド
	 * @param obj　ログ内容
	 */
	public static void i(Object obj) {
		if (IS_PRINT_INFO) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.i(TAG, getParentClassAndMethod());
			}

			if (obj == null) {
				android.util.Log.i(TAG, "null");
			} else {
				android.util.Log.i(TAG, obj.toString());
			}
		}
	}

	/**
	 * インフォログ出力メソッド
	 * @param tag　ログ出力する際のタグ
	 * @param mes　ログ内容
	 */
	public static void i(String tag, String mes) {
		if (IS_PRINT_INFO) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.i(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.i(tag, mes);
			}
		}
	}

	/**
	 * 詳細ログ出力メソッド
	 * @param obj　ログ内容
	 */
	public static void v(Object obj) {
		if (IS_PRINT_VERBOSE) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.v(TAG, getParentClassAndMethod());
			}

			if (obj == null) {
				android.util.Log.v(TAG, "null");
			} else {
				android.util.Log.v(TAG, obj.toString());
			}
		}
	}

	/**
	 * 詳細ログ出力メソッド
	 * @param tag　ログ出力する際のタグ
	 * @param mes　ログ内容
	 */
	public static void v(String tag, String mes) {
		if (IS_PRINT_VERBOSE) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.v(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.v(tag, mes);
			}
		}
	}

	/**
	 * 詳細ログ出力メソッド
	 * @param tag　ログ出力する際のタグ
	 * @param mes　ログ内容
	 * @param e　エラー内容
	 */
	public static void v(String tag, String mes, Exception e) {
		if (IS_PRINT_VERBOSE) {
			if (IS_PRINT_CLASS_AND_METHOD) {
				android.util.Log.v(tag, getParentClassAndMethod());
			}

			if (tag != null && mes != null) {
				android.util.Log.v(tag, mes, e);
			}
		}
	}
}
