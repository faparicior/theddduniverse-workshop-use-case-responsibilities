package framework.database

interface TransactionManager {
    fun beginTransaction()
    fun commit()
    fun rollback()
}
